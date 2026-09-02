<?php
header("Content-Type: application/json");
header("Cache-Control: public, max-age=120");
require __DIR__ . "/config.php";

$CATEGORY_SLUG_MAP = [
    "necklaces" => "necklaces",
    "earrings" => "earrings",
    "bracelets" => "bracelets",
    "rings" => "rings",
    "jewelry-sets" => "sets",
];

function wcFetch($path) {
    $ch = curl_init(WC_SITE . $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, WC_KEY . ":" . WC_SECRET);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code >= 400) {
        http_response_code(502);
        echo json_encode(["error" => "Could not load products"]);
        exit;
    }
    return json_decode($response, true);
}

$products = wcFetch("/wp-json/wc/v3/products?per_page=100&status=publish");

$result = array_map(function ($p) use ($CATEGORY_SLUG_MAP) {
    $meta = [];
    foreach ($p["meta_data"] as $m) {
        $meta[$m["key"]] = $m["value"];
    }

    $categorySlug = "sets";
    if (!empty($p["categories"])) {
        $rawSlug = $p["categories"][0]["slug"];
        $categorySlug = $CATEGORY_SLUG_MAP[$rawSlug] ?? $rawSlug;
    }

    $regularPrice = floatval($p["regular_price"]);
    $salePrice = $p["sale_price"] !== "" ? floatval($p["sale_price"]) : null;

    // Auto-show a "New" badge for anything published in the last 30 days,
    // unless a different badge was set manually.
    $badge = $meta["badge"] ?? "";
    if ($badge === "") {
        $daysOld = (time() - strtotime($p["date_created"])) / 86400;
        if ($daysOld <= 30) {
            $badge = "New";
        }
    }

    return [
        "id" => $p["id"],
        "name" => $p["name"],
        "category" => $categorySlug,
        "material" => $meta["material"] ?? "",
        "price" => $salePrice !== null ? $salePrice : $regularPrice,
        "oldPrice" => $salePrice !== null ? $regularPrice : null,
        "image" => !empty($p["images"]) ? $p["images"][0]["src"] : "images/products/placeholder.svg",
        "badge" => $badge,
        "featured" => ($meta["featured_home"] ?? "") === "true",
        "description" => wp_strip_tags($p["description"]),
        "care" => $meta["care"] ?? "",
        "dimensions" => $meta["dimensions"] ?? "",
        "inStock" => $p["stock_status"] === "instock",
    ];
}, $products);

function wp_strip_tags($html) {
    return trim(strip_tags($html));
}

echo json_encode($result);
