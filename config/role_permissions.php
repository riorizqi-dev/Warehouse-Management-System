<?php

use App\Models\User;

return [
    User::ROLE_SUPERADMIN => [
        'menus' => ['dashboard', 'master_data', 'orders', 'warehouse_ops', 'shipping', 'reports', 'settings', 'user_management', 'feature_toggle'],
        'actions' => ['*'],
    ],
    User::ROLE_ADMIN => [
        'menus' => ['dashboard', 'master_data', 'orders', 'warehouse_ops', 'shipping', 'reports', 'settings'],
        'actions' => [
            'barang.view', 'barang.create', 'barang.update', 'barang.delete',
            'kategori.view', 'kategori.manage',
            'customer.view', 'customer.create', 'customer.update',
            'order.view', 'order.create', 'order.reserve', 'order.reject',
            'order.picking', 'order.pack', 'order.ship', 'order.deliver',
            'stock.in', 'stock.out', 'stock.adjust',
            'shipment.view', 'shipment.update',
            'report.view',
        ],
    ],
    User::ROLE_STAFF_GUDANG => [
        'menus' => ['dashboard', 'master_data', 'orders', 'warehouse_ops', 'reports', 'settings'],
        'actions' => [
            'barang.view', 'barang.create', 'barang.update',
            'kategori.view',
            'order.view', 'order.picking', 'order.pack',
            'stock.in', 'stock.out', 'stock.adjust',
            'report.view',
        ],
    ],
    User::ROLE_STAFF_PENJUALAN => [
        'menus' => ['dashboard', 'master_data', 'orders', 'reports', 'settings'],
        'actions' => [
            'barang.view',
            'kategori.view',
            'customer.view', 'customer.create', 'customer.update',
            'order.view', 'order.create', 'order.reserve', 'order.reject',
            'shipment.view',
            'report.view',
        ],
    ],
    User::ROLE_KURIR => [
        'menus' => ['dashboard', 'orders', 'shipping', 'settings'],
        'actions' => [
            'order.view',
            'shipment.view', 'shipment.update',
            'order.ship', 'order.deliver',
        ],
    ],
    User::ROLE_CUSTOMER => [
        'menus' => ['customer_dashboard', 'customer_catalog', 'customer_orders', 'customer_profile'],
        'actions' => [
            'catalog.view', 'order.create_self', 'order.view_self',
        ],
    ],
];
