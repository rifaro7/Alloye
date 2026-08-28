<?php
// Minimal dependency-free SMTP client for sending mail via Gmail,
// since plain PHP mail() is unreliable/unauthenticated on this hosting.

function smtpSendMail($to, $subject, $body, $replyTo = null) {
    require_once __DIR__ . "/config.php";

    $host = "smtp.gmail.com";
    $port = 587;
    $username = SMTP_USERNAME;
    $password = SMTP_PASSWORD;

    $socket = @fsockopen($host, $port, $errno, $errstr, 15);
    if (!$socket) {
        return false;
    }

    $read = function () use ($socket) {
        $data = "";
        while ($line = fgets($socket, 515)) {
            $data .= $line;
            if (substr($line, 3, 1) === " ") break;
        }
        return $data;
    };

    $send = function ($cmd) use ($socket) {
        fwrite($socket, $cmd . "\r\n");
    };

    $read(); // greeting

    $send("EHLO alloye.shop");
    $read();

    $send("STARTTLS");
    $read();

    if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
        fclose($socket);
        return false;
    }

    $send("EHLO alloye.shop");
    $read();

    $send("AUTH LOGIN");
    $read();
    $send(base64_encode($username));
    $read();
    $send(base64_encode($password));
    $authResponse = $read();
    if (strpos($authResponse, "235") !== 0) {
        fclose($socket);
        return false;
    }

    $send("MAIL FROM:<$username>");
    $read();
    $send("RCPT TO:<$to>");
    $read();
    $send("DATA");
    $read();

    $headers = "From: Alloyé <$username>\r\n";
    $headers .= "To: <$to>\r\n";
    if ($replyTo) {
        $headers .= "Reply-To: <$replyTo>\r\n";
    }
    $headers .= "Subject: $subject\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $message = $headers . "\r\n" . $body . "\r\n.";
    $send($message);
    $sendResponse = $read();

    $send("QUIT");
    fclose($socket);

    return strpos($sendResponse, "250") === 0;
}
