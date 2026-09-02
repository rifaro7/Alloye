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

// Figure out real best sellers from actual order history, instead of a manually-assigned label.
$BEST_SELLER_THRESHOLD = 5; // minimum units sold to earn the badge
$soldCounts = [];
$orders = wcFetch("/wp-json/wc/v3/orders?per_page=100&status=processing,completed");
if (is_array($orders)) {
    foreach ($orders as $order) {
        foreach ($order["line_items"] as $item) {
            $pid = $item["product_id"];
            $soldCounts[$pid] = ($soldCounts[$pid] ?? 0) + $item["quantity"];
        }
    }
}

$result = array_map(function ($p) use ($CATEGORY_SLUG_MAP, $soldCounts, $BEST_SELLER_THRESHOLD) {
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

    // Badge priority: real "Best Seller" status (based on actual units sold) first,
    // then any manually-set badge (e.g. "Limited Edition"), then auto "New" for
    // anything published in the last 30 days.
    $unitsSold = $soldCounts[$p["id"]] ?? 0;
    if ($unitsSold >= $BEST_SELLER_THRESHOLD) {
        $badge = "Best Seller";
    } else {
        $badge = $meta["badge"] ?? "";
        if ($badge === "Best Seller") {
            $badge = ""; // don't trust a manually-set claim that isn't backed by real sales
        }
        if ($badge === "") {
            $daysOld = (time() - strtotime($p["date_created"])) / 86400;
            if ($daysOld <= 30) {
                $badge = "New";
            }
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
