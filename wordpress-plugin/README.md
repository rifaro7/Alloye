# Alloyé Products — WordPress Plugin

A lightweight product catalog for WordPress. No WooCommerce. Adds a
"Products" screen to your WordPress dashboard where you can add/edit
jewellery listings (name, photo, price, material, category, etc.),
and makes that data available for the website to read.

## Installing it (once you have WordPress hosting)

1. Zip the `alloye-products` folder (right-click it in Finder → Compress).
2. In your WordPress dashboard, go to **Plugins → Add New → Upload Plugin**.
3. Choose the zip file, click **Install Now**, then **Activate**.
4. A new **Products** item will appear in the left sidebar.

## Adding a product

1. **Products → Add New**
2. Fill in:
   - **Title** — the product name
   - **Featured Image** — the main photo
   - **Categories** (right sidebar) — pick Necklaces / Earrings / Bracelets / Rings / Sets
   - **Product Details** box — price, old/sale price, material, badge, care instructions, dimensions, SKU, availability, and whether it should show on the homepage
   - **Content box** — the product description
3. Click **Publish**.

## How the website reads this data

Once this plugin is active, your product data is available at:

```
https://your-domain.com/wp-json/alloye/v1/products
```

That's a small custom endpoint (not the default WordPress REST API) shaped
to match what the website's code already expects — so switching the site
from `js/products.js` to this endpoint is a small, contained change,
not a rewrite. Ask me to make that switch once this is installed on
real hosting and you've added a few products through the dashboard.
