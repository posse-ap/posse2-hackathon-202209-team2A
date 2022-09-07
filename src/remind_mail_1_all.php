<?php
require('dbconnect.php');
date_default_timezone_set('Asia/Tokyo');
mb_language('ja');
mb_internal_encoding('UTF-8');

// 明日と明後日を取得
$tomorrow_start  = date('Y-m-d 00:00:00',strtotime("+1 day"));
$tomorrow_end  = date('Y-m-d 23:59:59',strtotime("+1 day"));

echo $tomorrow_start;
echo $tomorrow_end;

// 明日中に行われるイベントのみを取得
$stmt_event = $db->prepare("SELECT * FROM events where '$tomorrow_start' < start_at AND start_at < '$tomorrow_end'");
$stmt_event->execute();
$tomorrow_events = $stmt_event->fetchAll();

// 全userを取得
$stmt_user = $db->prepare("SELECT * FROM users");
$stmt_user->execute();
$users = $stmt_user->fetchAll();

foreach ($tomorrow_events as $tomorrow_event) {
foreach ($users as $user) {

$user_name = $user['name'];
$to = $user_name;
$tomorrow_event_name = $tomorrow_event['name'];
$subject = <<<EOT
    ${tomorrow_event_name}リマインドメール（前日 @全員）
    EOT;
$body = "明日はいよいよ${tomorrow_event_name}イベント当日となっています！";
$headers = ["From"=>"system@posse-ap.com", "Content-Type"=>"text/plain; charset=UTF-8", "Content-Transfer-Encoding"=>"8bit"];

$event_date = $tomorrow_event['start_at'];
$body = <<<EOT
{$user_name}様


明日の ${event_date}より『${tomorrow_event_name}』を開催いたします！！！！
{$user_name}のご参加、楽しみにしています！

【イベント詳細】

EOT;

mb_send_mail($to, $subject, $body, $headers);
}
}
echo "メールを送信しました";