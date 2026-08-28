<?php
header("Content-Type: application/json");
require __DIR__ . "/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$body = json_decode(file_get_contents("php://input"), true);

$name = trim($body["name"] ?? "");
$email = trim($body["email"] ?? "");
$password = $body["password"] ?? "";

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["error" => "Please provide a name, valid email, and a password of at least 6 characters."]);
    exit;
}

$nameParts = explode(" ", $name, 2);

$payload = [
    "email" => $email,
    "first_name" => $nameParts[0],
    "last_name" => $nameParts[1] ?? "",
    "username" => $email,
    "password" => $password,
];

$ch = curl_init(WC_SITE . "/wp-json/wc/v3/customers");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_USERPWD, WC_KEY . ":" . WC_SECRET);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode >= 400) {
    $errorData = json_decode($response, true);
    $message = $errorData["message"] ?? "Could not create account.";
    if (strpos($errorData["code"] ?? "", "email_exists") !== false || strpos($errorData["code"] ?? "", "existing_user_email") !== false) {
        $message = "An account with this email already exists. Try logging in instead.";
    }
    http_response_code(400);
    echo json_encode(["error" => $message]);
    exit;
}

echo json_encode(["success" => true]);
