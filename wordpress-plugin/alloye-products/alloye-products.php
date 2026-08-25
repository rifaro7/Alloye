<?php
/**
 * Plugin Name: Alloyé Products
 * Description: A lightweight product catalog for Alloyé — no WooCommerce. Adds a "Products" screen to the WordPress dashboard and exposes the data over the REST API for the website to read.
 * Version: 1.0
 * Author: Alloyé
 */

if (!defined('ABSPATH')) {
    exit;
}

// ----- Register the "Product" post type -----
function alloye_register_product_post_type() {
    register_post_type('alloye_product', array(
        'labels' => array(
            'name' => 'Products',
            'singular_name' => 'Product',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
            'all_items' => 'All Products',
        ),
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'alloye-products',
        'menu_icon' => 'dashicons-tag',
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => false,
    ));
}
add_action('init', 'alloye_register_product_post_type');

// ----- Register the "Category" taxonomy (necklaces, earrings, bracelets, rings, sets) -----
function alloye_register_product_category() {
    register_taxonomy('alloye_category', 'alloye_product', array(
        'labels' => array(
            'name' => 'Categories',
            'singular_name' => 'Category',
        ),
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
    ));
}
add_action('init', 'alloye_register_product_category');

// ----- Default categories on plugin activation -----
function alloye_create_default_categories() {
    $defaults = array('Necklaces', 'Earrings', 'Bracelets', 'Rings', 'Sets');
    foreach ($defaults as $name) {
        if (!term_exists($name, 'alloye_category')) {
            wp_insert_term($name, 'alloye_category');
        }
    }
}
register_activation_hook(__FILE__, 'alloye_create_default_categories');

// ----- Custom fields (price, material, badge, etc.) -----
function alloye_register_product_meta_fields() {
    $fields = array(
        'alloye_price' => 'number',
        'alloye_old_price' => 'number',
        'alloye_material' => 'string',
        'alloye_badge' => 'string',
        'alloye_featured' => 'boolean',
        'alloye_care' => 'string',
        'alloye_dimensions' => 'string',
        'alloye_sku' => 'string',
        'alloye_stock_status' => 'string', // "in_stock" or "out_of_stock"
    );

    foreach ($fields as $key => $type) {
        register_post_meta('alloye_product', $key, array(
            'type' => $type,
            'single' => true,
            'show_in_rest' => true,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ));
    }
}
add_action('init', 'alloye_register_product_meta_fields');

// ----- Admin edit screen: the fields box -----
function alloye_add_product_meta_box() {
    add_meta_box(
        'alloye_product_details',
        'Product Details',
        'alloye_render_product_meta_box',
        'alloye_product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'alloye_add_product_meta_box');

function alloye_render_product_meta_box($post) {
    wp_nonce_field('alloye_save_product_meta', 'alloye_product_meta_nonce');

    $price = get_post_meta($post->ID, 'alloye_price', true);
    $old_price = get_post_meta($post->ID, 'alloye_old_price', true);
    $material = get_post_meta($post->ID, 'alloye_material', true);
    $badge = get_post_meta($post->ID, 'alloye_badge', true);
    $featured = get_post_meta($post->ID, 'alloye_featured', true);
    $care = get_post_meta($post->ID, 'alloye_care', true);
    $dimensions = get_post_meta($post->ID, 'alloye_dimensions', true);
    $sku = get_post_meta($post->ID, 'alloye_sku', true);
    $stock_status = get_post_meta($post->ID, 'alloye_stock_status', true) ?: 'in_stock';
    ?>
    <style>
        .alloye-field { margin-bottom: 16px; }
        .alloye-field label { display: block; font-weight: 600; margin-bottom: 4px; }
        .alloye-field input[type="text"],
        .alloye-field input[type="number"],
        .alloye-field textarea,
        .alloye-field select { width: 100%; max-width: 420px; }
        .alloye-row { display: flex; gap: 20px; flex-wrap: wrap; }
        .alloye-row .alloye-field { flex: 1; min-width: 200px; }
    </style>

    <div class="alloye-row">
        <div class="alloye-field">
            <label for="alloye_price">Price (৳)</label>
            <input type="number" step="1" id="alloye_price" name="alloye_price" value="<?php echo esc_attr($price); ?>" />
        </div>
        <div class="alloye-field">
            <label for="alloye_old_price">Old / Sale Price (৳, optional)</label>
            <input type="number" step="1" id="alloye_old_price" name="alloye_old_price" value="<?php echo esc_attr($old_price); ?>" />
        </div>
        <div class="alloye-field">
            <label for="alloye_sku">SKU</label>
            <input type="text" id="alloye_sku" name="alloye_sku" value="<?php echo esc_attr($sku); ?>" />
        </div>
    </div>

    <div class="alloye-row">
        <div class="alloye-field">
            <label for="alloye_material">Material</label>
            <input type="text" id="alloye_material" name="alloye_material" value="<?php echo esc_attr($material); ?>" placeholder="e.g. Stainless Steel, Cubic Zirconia" />
        </div>
        <div class="alloye-field">
            <label for="alloye_badge">Badge</label>
            <input type="text" id="alloye_badge" name="alloye_badge" value="<?php echo esc_attr($badge); ?>" placeholder="e.g. New, Sale, Best Seller" />
        </div>
        <div class="alloye-field">
            <label for="alloye_stock_status">Availability</label>
            <select id="alloye_stock_status" name="alloye_stock_status">
                <option value="in_stock" <?php selected($stock_status, 'in_stock'); ?>>In Stock</option>
                <option value="out_of_stock" <?php selected($stock_status, 'out_of_stock'); ?>>Out of Stock</option>
            </select>
        </div>
    </div>

    <div class="alloye-field">
        <label for="alloye_dimensions">Dimensions</label>
        <input type="text" id="alloye_dimensions" name="alloye_dimensions" value="<?php echo esc_attr($dimensions); ?>" placeholder="e.g. Chain length: 18 inches" />
    </div>

    <div class="alloye-field">
        <label for="alloye_care">Care Instructions</label>
        <textarea id="alloye_care" name="alloye_care" rows="3"><?php echo esc_textarea($care); ?></textarea>
    </div>

    <div class="alloye-field">
        <label>
            <input type="checkbox" id="alloye_featured" name="alloye_featured" value="1" <?php checked($featured, '1'); ?> />
            Show on homepage (Featured Collection)
        </label>
    </div>

    <p style="color:#666;">Use the main <strong>Title</strong> field above for the product name, the <strong>editor</strong> box for its description, the <strong>Featured Image</strong> for its main photo, and the <strong>Categories</strong> panel on the right to file it under Necklaces / Earrings / Bracelets / Rings / Sets.</p>
    <?php
}

function alloye_save_product_meta($post_id) {
    if (!isset($_POST['alloye_product_meta_nonce']) || !wp_verify_nonce($_POST['alloye_product_meta_nonce'], 'alloye_save_product_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $text_fields = array('alloye_material', 'alloye_badge', 'alloye_dimensions', 'alloye_sku', 'alloye_stock_status');
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    $number_fields = array('alloye_price', 'alloye_old_price');
    foreach ($number_fields as $field) {
        if (isset($_POST[$field]) && $_POST[$field] !== '') {
            update_post_meta($post_id, $field, floatval($_POST[$field]));
        } else {
            delete_post_meta($post_id, $field);
        }
    }

    if (isset($_POST['alloye_care'])) {
        update_post_meta($post_id, 'alloye_care', sanitize_textarea_field($_POST['alloye_care']));
    }

    update_post_meta($post_id, 'alloye_featured', isset($_POST['alloye_featured']) ? '1' : '0');
}
add_action('save_post_alloye_product', 'alloye_save_product_meta');

// ----- Admin list screen: show price and category as columns -----
function alloye_product_columns($columns) {
    $columns['alloye_price'] = 'Price';
    $columns['alloye_stock'] = 'Availability';
    return $columns;
}
add_filter('manage_alloye_product_posts_columns', 'alloye_product_columns');

function alloye_product_column_content($column, $post_id) {
    if ($column === 'alloye_price') {
        $price = get_post_meta($post_id, 'alloye_price', true);
        echo $price ? '৳' . esc_html($price) : '—';
    }
    if ($column === 'alloye_stock') {
        $status = get_post_meta($post_id, 'alloye_stock_status', true);
        echo $status === 'out_of_stock' ? 'Out of Stock' : 'In Stock';
    }
}
add_action('manage_alloye_product_posts_custom_column', 'alloye_product_column_content', 10, 2);

// ----- Simplified REST endpoint: /wp-json/alloye/v1/products -----
// Returns data shaped to match the website's existing products.js structure,
// so the frontend swap is a small change rather than a rewrite.
function alloye_register_rest_route() {
    register_rest_route('alloye/v1', '/products', array(
        'methods' => 'GET',
        'callback' => 'alloye_get_products_rest',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'alloye_register_rest_route');

function alloye_get_products_rest() {
    $posts = get_posts(array(
        'post_type' => 'alloye_product',
        'post_status' => 'publish',
        'numberposts' => -1,
    ));

    $products = array();

    foreach ($posts as $post) {
        $terms = get_the_terms($post->ID, 'alloye_category');
        $category = $terms && !is_wp_error($terms) ? strtolower($terms[0]->name) : '';

        $image_url = get_the_post_thumbnail_url($post->ID, 'large');

        $products[] = array(
            'id' => $post->ID,
            'name' => $post->post_title,
            'category' => $category,
            'material' => get_post_meta($post->ID, 'alloye_material', true),
            'price' => (float) get_post_meta($post->ID, 'alloye_price', true),
            'oldPrice' => get_post_meta($post->ID, 'alloye_old_price', true) ?: null,
            'image' => $image_url ?: '',
            'badge' => get_post_meta($post->ID, 'alloye_badge', true),
            'featured' => get_post_meta($post->ID, 'alloye_featured', true) === '1',
            'description' => wp_strip_all_tags($post->post_content),
            'care' => get_post_meta($post->ID, 'alloye_care', true),
            'dimensions' => get_post_meta($post->ID, 'alloye_dimensions', true),
            'inStock' => get_post_meta($post->ID, 'alloye_stock_status', true) !== 'out_of_stock',
        );
    }

    return rest_ensure_response($products);
}
