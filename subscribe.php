<?php
header("Content-Type: application/json");
require __DIR__ . "/config.php";
require __DIR__ . "/smtp-mailer.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$body = json_decode(file_get_contents("php://input"), true);
$email = trim($body["email"] ?? "");

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "Please enter a valid email address."]);
    exit;
}

$file = __DIR__ . "/subscribers.csv";
$alreadySubscribed = false;

if (file_exists($file)) {
    $existing = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($existing as $line) {
        $parts = str_getcsv($line, ",", '"', "\\");
        if (isset($parts[0]) && strtolower($parts[0]) === strtolower($email)) {
            $alreadySubscribed = true;
            break;
        }
    }
}

if (!$alreadySubscribed) {
    $row = '"' . str_replace('"', '""', $email) . '","' . date("Y-m-d H:i:s") . '"' . "\n";
    file_put_contents($file, $row, FILE_APPEND | LOCK_EX);

    smtpSendMail(STORE_EMAIL, "New newsletter subscriber — Alloyé", "New subscriber: $email");
}

echo json_encode(["success" => true]);
