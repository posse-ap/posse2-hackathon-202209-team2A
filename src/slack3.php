<?php
require('dbconnect.php');

// 三日後の始まりと終わり
$three_days_later_start  = date('Y-m-d 00:00:00', strtotime("+3 day"));
$three_days_later_end  = date('Y-m-d 23:59:59', strtotime("+3 day"));

// SELECT events.name, events.start_at, users.name,event_attendance.status FROM events LEFT JOIN event_attendance ON event_attendance.event_id = events.id LEFT JOIN users ON event_attendance.user_id = users.id WHERE events.start_at >= CURDATE() AND event_attendance.status='presence' ORDER BY events.name DESC;
$stmt = $db->prepare("SELECT events.name, events.detail, events.start_at, events.end_at, users.slack_id
    FROM events 
    CROSS JOIN users 
    WHERE events.start_at >= CURDATE() 
    AND NOT EXISTS (
     select * from event_attendance 
    where event_attendance.user_id = users.id 
    and event_attendance.event_id = events.id) 
    and '$three_days_later_start' < start_at AND start_at < '$three_days_later_end'
    and events.id = 6;
");
$stmt->execute();
$event = $stmt->fetch();

// 未登録者数取得
$stmt = $db->prepare("SELECT events.name, events.detail, events.start_at, events.end_at, users.slack_id
    FROM events 
    CROSS JOIN users 
    WHERE events.start_at >= CURDATE() 
    AND NOT EXISTS (
     select * from event_attendance 
    where event_attendance.user_id = users.id 
    and event_attendance.event_id = events.id) 
    and '$three_days_later_start' < start_at AND start_at < '$three_days_later_end'
    and events.id = 6;
");
$stmt->execute();
$participants = $stmt->fetchAll();


$name = $event['name'];
$detail = $event['detail'];
$start_at = $event['start_at'];
$end_at = $event['end_at'];
$slack_id = $event['slack_id'];


$array = [];
foreach ($participants as $participant) {
  $array[] = $participant['slack_id'];
}
$mentions = implode("><@", $array);

$url = 'https://hooks.slack.com/services/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';
$message = [
  "channel" => "#notify",
  "username" => "イベント通知管理ボット",
  "text" => "
    ------------------------------------------
    イベント【3日前】です！入力してください！

    ■ イベント名
    $name

    ■ 内容
    $detail

    ■ 開催日時
    $start_at ~ $end_at
    
    ■ 未回答者
    <@$mentions>
    ------------------------------------------
    ",
];

// 配列用意、ループで文字列結合（スペース入れながら）
// そこで変数差し込む

$ch = curl_init();
$options = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => http_build_query([
    'payload' => json_encode($message)
  ])
];
curl_setopt_array($ch, $options);
curl_exec($ch);
curl_close($ch);

// SELECT events.name, events.start_at, users.name,event_attendance.status FROM events LEFT JOIN event_attendance ON event_attendance.event_id = events.id LEFT JOIN users ON event_attendance.user_id = users.id WHERE events.start_at >= CURDATE() AND event_attendance.status='presence' ORDER BY events.name DESC;
$stmt = $db->prepare("SELECT events.name, events.detail, events.start_at, events.end_at, users.slack_id
    FROM events 
    CROSS JOIN users 
    WHERE events.start_at >= CURDATE() 
    AND NOT EXISTS (
     select * from event_attendance 
    where event_attendance.user_id = users.id 
    and event_attendance.event_id = events.id) 
    and '$three_days_later_start' < start_at AND start_at < '$three_days_later_end'
    and events.id = 8;
");
$stmt->execute();
$event2 = $stmt->fetch();

// 未登録者数取得
$stmt = $db->prepare("SELECT events.name, events.detail, events.start_at, events.end_at, users.slack_id
    FROM events 
    CROSS JOIN users 
    WHERE events.start_at >= CURDATE() 
    AND NOT EXISTS (
     select * from event_attendance 
    where event_attendance.user_id = users.id 
    and event_attendance.event_id = events.id) 
    and '$three_days_later_start' < start_at AND start_at < '$three_days_later_end'
    and events.id = 8;
");
$stmt->execute();
$participants2 = $stmt->fetchAll();


$name2 = $event2['name'];
$detail2 = $event2['detail'];
$start_at2 = $event2['start_at'];
$end_at2 = $event2['end_at'];
$slack_id2 = $event2['slack_id'];


$array2 = [];
foreach ($participants2 as $participant2) {
  $array2[] = $participant2['slack_id'];
}
$mentions2 = implode("><@", $array2);


$url = 'https://hooks.slack.com/services/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';
$message = [
  "channel" => "#notify",
  "username" => "イベント通知管理ボット",
  "text" => "
    ------------------------------------------
    イベント【3日前】です！入力してください！

    ■ イベント名
    $name2

    ■ 内容
    $detail2

    ■ 開催日時
    $start_at2 ~ $end_at2
    
    ■ 未回答者
    <@$mentions2>
    ------------------------------------------
    ",
];

// 配列用意、ループで文字列結合（スペース入れながら）
// そこで変数差し込む

$ch2 = curl_init();
$options = [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => http_build_query([
    'payload' => json_encode($message)
  ])
];
curl_setopt_array($ch2, $options);
curl_exec($ch2);
curl_close($ch2);
