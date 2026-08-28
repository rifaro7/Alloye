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

$name = trim($body["name"] ?? "");
$email = trim($body["email"] ?? "");
$message = trim($body["message"] ?? "");

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$message) {
    http_response_code(400);
    echo json_encode(["error" => "Please fill in all fields with a valid email."]);
    exit;
}

$subject = "New message from $name — Alloyé website";
$body_text = "Name: $name\nEmail: $email\n\nMessage:\n$message";

$sent = smtpSendMail(STORE_EMAIL, $subject, $body_text, $email);

if (!$sent) {
    http_response_code(502);
    echo json_encode(["error" => "Could not send message. Please try again."]);
    exit;
}

echo json_encode(["success" => true]);
