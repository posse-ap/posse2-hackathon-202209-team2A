<?php 

define("SLACK_SIGNING_SECRET","0f64190adc00697e6bdb1aef07c3b7f4");
define("SLACK_BOT_ACCESS_TOKEN","xoxb-4054978785924-4045945148150-JGFo8sICARqXIqfd2SUr7CY7");

$headers = [
    'Authorization: Bearer SLACK_BOT_ACCESS_TOKEN', 
    'Content-Type: application/json;charset=utf-8'
];

$url = "https://slack.com/api/chat.postMessage"; 

$post_fields = [
    "channel" => "@general",
    "text" => "テスト",
    "as_user" => false
];

$options = [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($post_fields) 
];

$ch = curl_init();

curl_setopt_array($ch, $options);

$result = curl_exec($ch); 

curl_close($ch);

echo $result;