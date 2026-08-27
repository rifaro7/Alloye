<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

require __DIR__ . "/config.php";

// Maps this site's local product IDs (js/products.js) to the real WooCommerce product IDs
$PRODUCT_ID_MAP = [
    1 => 17, 2 => 58, 3 => 19, 4 => 21, 5 => 59, 6 => 23, 7 => 25, 8 => 27,
    9 => 29, 10 => 60, 11 => 31, 12 => 61, 13 => 33, 14 => 35, 15 => 37,
    16 => 39, 18 => 41, 20 => 43, 21 => 45, 22 => 47, 23 => 49, 24 => 51,
    25 => 53, 26 => 55, 27 => 57,
];

$DELIVERY_LABELS = [
    "inside" => "Inside Dhaka",
    "outside" => "Outside Dhaka",
];

$body = json_decode(file_get_contents("php://input"), true);

if (!$body) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid request body"]);
    exit;
}

// --- Basic validation ---
$required = ["customer", "address", "items", "deliveryMethod", "paymentMethod", "subtotal", "delivery", "total"];
foreach ($required as $field) {
    if (!isset($body[$field])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing field: $field"]);
        exit;
    }
}
if (empty($body["items"]) || !is_array($body["items"])) {
    http_response_code(400);
    echo json_encode(["error" => "Cart is empty"]);
    exit;
}
$customer = $body["customer"];
if (empty($customer["name"]) || empty($customer["email"]) || empty($customer["phone"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing customer details"]);
    exit;
}

// --- Build WooCommerce line items ---
$lineItems = [];
foreach ($body["items"] as $item) {
    $localId = intval($item["id"]);
    if (!isset($PRODUCT_ID_MAP[$localId])) {
        http_response_code(400);
        echo json_encode(["error" => "Unknown product id: $localId"]);
        exit;
    }
    $lineItems[] = [
        "product_id" => $PRODUCT_ID_MAP[$localId],
        "quantity" => max(1, intval($item["qty"])),
    ];
}

$addr = $body["address"];
$nameParts = explode(" ", trim($customer["name"]), 2);
$firstName = $nameParts[0];
$lastName = $nameParts[1] ?? "";
$addressLine1 = trim(($addr["house"] ?? "") . ", " . ($addr["road"] ?? ""));

$orderPayload = [
    "payment_method" => "cod",
    "payment_method_title" => "Cash on Delivery",
    "set_paid" => false,
    "status" => "processing",
    "billing" => [
        "first_name" => $firstName,
        "last_name" => $lastName,
        "address_1" => $addressLine1,
        "address_2" => $addr["area"] ?? "",
        "city" => $addr["city"] ?? "",
        "state" => $addr["district"] ?? "",
        "postcode" => $addr["postalCode"] ?? "",
        "country" => "BD",
        "email" => $customer["email"],
        "phone" => $customer["phone"],
    ],
    "shipping" => [
        "first_name" => $firstName,
        "last_name" => $lastName,
        "address_1" => $addressLine1,
        "address_2" => $addr["area"] ?? "",
        "city" => $addr["city"] ?? "",
        "state" => $addr["district"] ?? "",
        "postcode" => $addr["postalCode"] ?? "",
        "country" => "BD",
    ],
    "line_items" => $lineItems,
    "shipping_lines" => [
        [
            "method_id" => "flat_rate",
            "method_title" => $DELIVERY_LABELS[$body["deliveryMethod"]] ?? "Delivery",
            "total" => strval($body["delivery"]),
        ],
    ],
];

// --- Create the order in WooCommerce ---
$ch = curl_init(WC_SITE . "/wp-json/wc/v3/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderPayload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_USERPWD, WC_KEY . ":" . WC_SECRET);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

if ($curlError || $httpCode >= 400) {
    http_response_code(502);
    echo json_encode(["error" => "Could not create order", "detail" => $curlError ?: $response]);
    exit;
}

$order = json_decode($response, true);

echo json_encode([
    "success" => true,
    "orderId" => $order["id"],
    "orderNumber" => $order["number"],
]);
