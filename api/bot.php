<?php

$TOKEN = "7639495089:AAHRLM8SAJBREn52pL786etbei9v61MqxMw";
$API_URL = "https://api.telegram.org/bot$TOKEN/";

// Получаем данные
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Вместо файла пишем в системный лог Vercel (error_log отправляет данные в панель управления)
error_log("Incoming Update: " . $input);

if (empty($input)) {
    echo "Bot is running on Vercel!";
    exit;
}

if (isset($update['message'])) {
    $chat_id = $update['message']['chat']['id'];
    $text = $update['message']['text'] ?? "";
    $name = $update['message']['from']['first_name'] ?? "User";

    if ($text === "/start") {
        $reply = "Привет, $name! Я работаю на Vercel 🚀";
    } else {
        $reply = "Вы сказали: $text";
    }

    send_message($chat_id, $reply);
}

function send_message($chat_id, $text) {
    global $API_URL;
    $url = $API_URL . "sendMessage";
    $post_fields = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
    $res = curl_exec($ch);
    error_log("Telegram Response: " . $res);
    curl_close($ch);
}