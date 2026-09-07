<?php
header("Content-Type: application/json");
require __DIR__ . "/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["valid" => false, "error" => "Method not allowed"]);
    exit;
}

$body = json_decode(file_get_contents("php://input"), true);
$code = trim($body["code"] ?? "");
$subtotal = floatval($body["subtotal"] ?? 0);

if ($code === "") {
    echo json_encode(["valid" => false, "error" => "Please enter a coupon code."]);
    exit;
}

$ch = curl_init(WC_SITE . "/wp-json/wc/v3/coupons?code=" . urlencode($code));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, WC_KEY . ":" . WC_SECRET);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$response = curl_exec($ch);
$results = json_decode($response, true);

if (!is_array($results) || empty($results)) {
    echo json_encode(["valid" => false, "error" => "Invalid or expired coupon code."]);
    exit;
}

$coupon = $results[0];

// Expiry check
if (!empty($coupon["date_expires"]) && strtotime($coupon["date_expires"]) < time()) {
    echo json_encode(["valid" => false, "error" => "This coupon has expired."]);
    exit;
}

// Usage limit check
if (!empty($coupon["usage_limit"]) && intval($coupon["usage_count"]) >= intval($coupon["usage_limit"])) {
    echo json_encode(["valid" => false, "error" => "This coupon has reached its usage limit."]);
    exit;
}

// Minimum / maximum spend checks
$minAmount = floatval($coupon["minimum_amount"] ?? 0);
if ($minAmount > 0 && $subtotal < $minAmount) {
    echo json_encode(["valid" => false, "error" => "This coupon requires a minimum order of ৳" . number_format($minAmount) . "."]);
    exit;
}
$maxAmount = floatval($coupon["maximum_amount"] ?? 0);
if ($maxAmount > 0 && $subtotal > $maxAmount) {
    echo json_encode(["valid" => false, "error" => "This coupon only applies to orders up to ৳" . number_format($maxAmount) . "."]);
    exit;
}

$discountType = $coupon["discount_type"]; // percent, fixed_cart, or fixed_product
$amount = floatval($coupon["amount"]);

echo json_encode([
    "valid" => true,
    "discountType" => ($discountType === "percent") ? "percent" : "fixed",
    "amount" => $amount,
]);
