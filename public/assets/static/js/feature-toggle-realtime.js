/**
 * Feature Toggle Real-time Updates
 * Mendengarkan perubahan fitur dan update sidebar secara otomatis
 */

(function() {
    'use strict';

    // Cek apakah Laravel Echo tersedia
    const hasEcho = typeof Echo !== 'undefined';
    
    // Fallback: Polling jika Echo tidak tersedia
    let pollingInterval = null;
    let lastFeatureHash = null;

    /**
     * Generate hash dari features untuk detect perubahan
     */
    function getFeatureHash() {
        const items = document.querySelectorAll('.feature-menu-item');
        return Array.from(items).map(item => {
            return item.getAttribute('data-feature-key') + ':' + 
                   (item.style.display !== 'none' ? '1' : '0');
        }).join('|');
    }

    /**
     * Update sidebar dengan data features baru
     */
    function updateSidebar(features) {
        const menuContainer = document.querySelector('.sidebar-menu .menu');
        if (!menuContainer) return;

        // Hapus semua feature menu items yang dinamis
        const existingItems = menuContainer.querySelectorAll('.feature-menu-item');
        existingItems.forEach(item => item.remove());

        // Cari posisi untuk insert (setelah Dashboard, sebelum Pengaturan)
        const dashboardItem = menuContainer.querySelector('li:has(a[href*="dashboard"])');
        const pengaturanItem = menuContainer.querySelector('li:has(a[href*="pengaturan"])');
        const insertBefore = pengaturanItem || menuContainer.lastElementChild;

        // Tambahkan features baru
        features.forEach(feature => {
            if (!feature.route_name) return;

            const featureKey = (feature.key || '').toLowerCase();
            const featureRouteName = (feature.route_name || '').toLowerCase();
            if (featureKey === 'dashboard' || featureRouteName === 'dashboard') {
                return;
            }

            const li = document.createElement('li');
            li.className = 'sidebar-item feature-menu-item';
            li.setAttribute('data-feature-key', feature.key);
            li.setAttribute('data-feature-id', feature.id);

            const icon = feature.icon || 'bi-circle';

            // Build route URL - handle both 'route.name' format and direct URLs
            let routeUrl = '#';
            try {
                // Try to get route from Laravel routes (if available)
                if (typeof window.laravelRoutes !== 'undefined' && window.laravelRoutes[feature.route_name]) {
                    routeUrl = window.laravelRoutes[feature.route_name];
                } else {
                    // Fallback: convert route name to URL (handle common patterns)
                    if (feature.route_name.includes('.')) {
                        const parts = feature.route_name.split('.');
                        routeUrl = '/' + parts.join('/');
                    } else {
                        routeUrl = '/' + feature.route_name;
                    }
                }
            } catch (e) {
                routeUrl = '#'; // Fallback to # if error
            }

            // Check if current route is active
            const currentPath = window.location.pathname;
            let routePath = null;
            try {
                const normalizedUrl = new URL(routeUrl, window.location.origin);
                routePath = normalizedUrl.pathname.replace(/\/+$/, '');
            } catch (e) {
                routePath = null;
            }

            if (routePath) {
                const normalizedCurrent = currentPath.replace(/\/+$/, '');
                if (
                    normalizedCurrent === routePath ||
                    normalizedCurrent.startsWith(routePath + '/')
                ) {
                    li.classList.add('active');
                }
            }

            li.innerHTML = `
                <a href="${routeUrl}" class="sidebar-link">
                    <i class="bi ${icon}"></i>
                    <span>${feature.name}</span>
                </a>
            `;

            menuContainer.insertBefore(li, insertBefore);
        });
    }

    /**
     * Load features dari API
     */
    async function loadFeatures() {
        try {
            const response = await fetch('/api/features/enabled');
            if (!response.ok) return;
            
            const features = await response.json();
            const currentHash = JSON.stringify(features.map(f => f.key + ':' + f.enabled));
            
            if (currentHash !== lastFeatureHash) {
                updateSidebar(features);
                lastFeatureHash = currentHash;
            }
        } catch (error) {
            console.error('Error loading features:', error);
        }
    }

    /**
     * Handle feature update dari broadcast
     */
    function handleFeatureUpdate(data) {
        const { action, feature } = data;

        if (action === 'deleted') {
            // Hapus menu item
            const item = document.querySelector(`.feature-menu-item[data-feature-key="${feature.key}"]`);
            if (item) {
                item.remove();
            }
        } else if (action === 'created' || action === 'updated') {
            // Reload semua features
            loadFeatures();
        }
    }

    /**
     * Initialize real-time updates
     */
    function init() {
        // Load initial features
        loadFeatures();

        if (hasEcho) {
            // Menggunakan Laravel Echo untuk real-time
            try {
                Echo.channel('feature-toggles')
                    .listen('.feature.updated', (data) => {
                        handleFeatureUpdate(data);
                    });
            } catch (error) {
                console.warn('Echo not configured, falling back to polling');
                startPolling();
            }
        } else {
            // Fallback: Polling setiap 5 detik
            startPolling();
        }
    }

    /**
     * Start polling sebagai fallback
     */
    function startPolling() {
        if (pollingInterval) return;
        
        pollingInterval = setInterval(() => {
            loadFeatures();
        }, 5000); // Poll setiap 5 detik
    }

    /**
     * Stop polling
     */
    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    // Initialize ketika DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Cleanup saat unload
    window.addEventListener('beforeunload', stopPolling);

})();

