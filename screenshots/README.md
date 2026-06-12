# Screenshots Directory

This directory contains screenshots for the Warehouse Management System documentation.

## Required Screenshots

To complete the README documentation, please add the following screenshots:

### 1. dashboard.png
- **What to capture**: Main admin dashboard
- **Show**: Key metrics, charts, quick actions, navigation menu
- **Size**: 1920x1080 (full screen) or 1280x720
- **Format**: PNG

### 2. login.png
- **What to capture**: Login page
- **Show**: Authentication form, branding, clean interface
- **Size**: 1920x1080 or 1280x720
- **Format**: PNG

### 3. products.png
- **What to capture**: Product management page (Data Barang)
- **Show**: Product list, add/edit forms, search functionality, stock levels
- **Size**: 1920x1080 or 1280x720
- **Format**: PNG

### 4. inventory.png
- **What to capture**: Inventory overview page
- **Show**: Stock summary, inventory logs, stock movements
- **Size**: 1920x1080 or 1280x720
- **Format**: PNG

### 5. stock-in.png
- **What to capture**: Add stock operation
- **Show**: Stock in form, product selection, quantity input
- **Size**: 1920x1080 or 1280x720
- **Format**: PNG

### 6. stock-out.png
- **What to capture**: Stock out operation
- **Show**: Stock out form, product selection, quantity tracking
- **Size**: 1920x1080 or 1280x720
- **Format**: PNG

### 7. users.png
- **What to capture**: User management page (Super Admin view)
- **Show**: User list, roles, add/edit user forms
- **Size**: 1920x1080 or 1280x720
- **Format**: PNG

### 8. reports.png
- **What to capture**: Reports page (Laporan)
- **Show**: Report filters, data tables, export options
- **Size**: 1920x1080 or 1280x720
- **Format**: PNG

## Bonus Screenshots (Optional but Recommended)

### 9. orders.png
- **What to capture**: Order management page
- **Show**: Order list, status tracking, order details

### 10. customer-portal.png
- **What to capture**: Customer dashboard/catalog
- **Show**: Product catalog, shopping cart, customer view

### 11. order-workflow.png
- **What to capture**: Order detail page showing workflow
- **Show**: Order status progression, timeline

### 12. feature-toggles.png
- **What to capture**: Feature toggle management
- **Show**: Feature list, enable/disable controls

## Screenshot Guidelines

### Best Practices
1. **Use real data** (but avoid sensitive information)
2. **Clean interface**: Close unnecessary browser tabs/windows
3. **Proper zoom level**: 100% browser zoom
4. **Hide personal info**: Blur/remove any personal data
5. **Good lighting**: Clear, readable text and UI elements
6. **Professional**: Use meaningful dummy data

### Tools for Screenshots
- **Windows**: Win + Shift + S (Snipping Tool)
- **Chrome DevTools**: Device toolbar for consistent sizing
- **Third-party**: ShareX, Greenshot, Lightshot

### Image Optimization
After capturing screenshots, optimize them:
```bash
# Using online tools
- TinyPNG.com
- Squoosh.app
- ImageOptim (Mac)

# Or via command line
npm install -g imagemin-cli
imagemin screenshots/*.png --out-dir=screenshots/optimized
```

## How to Take Screenshots

### Step 1: Run the Application
```bash
# From project root
php artisan serve
```

### Step 2: Login with Different Roles
Create test users for different roles to capture various views:
- Super Admin view
- Warehouse Staff view
- Sales Staff view
- Customer view

### Step 3: Populate Test Data
Add sample products, orders, and customers for realistic screenshots.

### Step 4: Capture Screenshots
Use the guidelines above to capture each required screenshot.

### Step 5: Name Files Correctly
Save files with exact names as listed above (lowercase, with hyphens).

### Step 6: Update Git
```bash
git add screenshots/*.png
git commit -m "docs: Add application screenshots"
git push origin master
```

## Current Status

- [ ] dashboard.png
- [ ] login.png
- [ ] products.png
- [ ] inventory.png
- [ ] stock-in.png
- [ ] stock-out.png
- [ ] users.png
- [ ] reports.png

Once all screenshots are added, the README will be complete and portfolio-ready!
