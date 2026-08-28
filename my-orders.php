<?php
header("Content-Type: application/json");
require __DIR__ . "/config.php";

$authHeader = $_SERVER["HTTP_AUTHORIZATION"] ?? ($_SERVER["REDIRECT_HTTP_AUTHORIZATION"] ?? "");

if (!preg_match("/Bearer\s+(.+)/i", $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}
$token = $matches[1];

// Verify the token by asking WordPress who it belongs to
$ch = curl_init(WC_SITE . "/wp-json/wp/v2/users/me");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$meResponse = curl_exec($ch);
$meCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($meCode >= 400) {
    http_response_code(401);
    echo json_encode(["error" => "Session expired, please log in again."]);
    exit;
}

$me = json_decode($meResponse, true);
$userId = $me["id"] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(["error" => "Could not identify account."]);
    exit;
}

// Get the customer's real name (WordPress' display_name defaults to their email, so use WooCommerce's first/last name instead)
$ch = curl_init(WC_SITE . "/wp-json/wc/v3/customers/$userId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, WC_KEY . ":" . WC_SECRET);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$customerResponse = curl_exec($ch);
$customer = json_decode($customerResponse, true);
$fullName = trim(($customer["first_name"] ?? "") . " " . ($customer["last_name"] ?? ""));

// Now fetch only this customer's orders, using the secret store key server-side
$ch = curl_init(WC_SITE . "/wp-json/wc/v3/orders?customer=$userId&per_page=50&orderby=date&order=desc");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, WC_KEY . ":" . WC_SECRET);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$ordersResponse = curl_exec($ch);
$ordersCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($ordersCode >= 400) {
    http_response_code(502);
    echo json_encode(["error" => "Could not load orders."]);
    exit;
}

$orders = json_decode($ordersResponse, true);

$simplified = array_map(function ($o) {
    return [
        "id" => $o["number"],
        "date" => $o["date_created"],
        "status" => $o["status"],
        "total" => $o["total"],
        "items" => array_map(function ($item) {
            return ["name" => $item["name"], "quantity" => $item["quantity"]];
        }, $o["line_items"]),
    ];
}, $orders);

echo json_encode(["success" => true, "orders" => $simplified, "name" => $fullName]);
