<?php
require('dbconnect.php');
date_default_timezone_set('Asia/Tokyo');
mb_language('ja');
mb_internal_encoding('UTF-8');

// 明日の始まりと終わり
$three_days_later_start  = date('Y-m-d 00:00:00', strtotime("+3 day"));
$three_days_later_end  = date('Y-m-d 23:59:59', strtotime("+3 day"));

echo $three_days_later_start;
echo $three_days_later_end;

// 明日中に行われるイベントのみを取得
$stmt_event = $db->prepare("SELECT * FROM events where '$three_days_later_start' < start_at AND start_at < '$three_days_later_end'");
$stmt_event->execute();
$three_days_later_events = $stmt_event->fetchAll();

// 全userを取得
$stmt_user = $db->prepare("SELECT * FROM users");
$stmt_user->execute();
$users = $stmt_user->fetchAll();

foreach ($three_days_later_events as $three_days_later_event) {

foreach ($users as $user) {

$user_name = $user['name'];
$event_detail = $three_days_later_event['detail'];

$to = $user_name;
$three_days_later_event_name = $three_days_later_event['name'];
$subject = <<<EOT
    ${three_days_later_event_name}リマインドメール（３日前 @全員）
    EOT;
$body = "明日はいよいよ${three_days_later_event_name}イベント当日となっています！";
$headers = ["From"=>"system@posse-ap.com", "Content-Type"=>"text/plain; charset=UTF-8", "Content-Transfer-Encoding"=>"8bit"];

$event_date = $three_days_later_event['start_at'];
$body = <<<EOT
{$user_name}様


明日の ${event_date}より『${three_days_later_event_name}』を開催いたします！！！！
{$user_name}のご参加、楽しみにしています！

【イベント詳細】

{$event_detail}

EOT;

mb_send_mail($to, $subject, $body, $headers);
}
}
echo "メールを送信しました";
