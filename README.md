# Alloyé

A premium jewelry e-commerce website for the Bangladesh market — minimal, elegant, and modern, with a waterproof stainless steel jewelry collection.

## About

Alloyé is a jewelry brand offering rings, necklaces, earrings, bracelets, and jewelry sets. This repository contains the customer-facing website.

## Tech Stack

- **HTML / CSS / JavaScript** — static multi-page site
- **WooCommerce (headless)** — product data management on `cms.alloye.shop`, connected via the WooCommerce REST API

## Running Locally

Serve the folder with any static file server, for example:

```bash
python3 -m http.server 8090
```

Then open `http://localhost:8090` in your browser.

## Project Structure

```
css/                stylesheet
js/                  product data, cart logic, main scripts
images/              logo, hero image, product photos
*.html               site pages (home, shop, product, cart, checkout, account, etc.)
wordpress-plugin/    alternate lightweight product-catalog plugin (not currently used)
```

## License

All rights reserved. This code is proprietary and may not be copied, modified, or distributed without explicit permission from the owner.

© 2026 Alloyé. All rights reserved.
