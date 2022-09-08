<?php 
require('dbconnect.php');

// 明日の始まりと終わり
$tomorrow_start  = date('Y-m-d 00:00:00', strtotime("+1 day"));
$tomorrow_end  = date('Y-m-d 23:59:59', strtotime("+1 day"));

// 明日中に行われるイベントのみを取得 同時にuserにおいて、statusがpresence（参加）となってる人のみ取得
$stmt = $db->prepare("SELECT events.name, events.detail, events.start_at, events.end_at, users.slack_id FROM events JOIN event_attendance ON event_attendance.event_id = events.id LEFT JOIN users ON event_attendance.user_id = users.id
WHERE '$tomorrow_start' < events.start_at 
AND events.start_at < '$tomorrow_end' 
AND event_attendance.status = 'presence'
");
$stmt->execute();
$event = $stmt->fetch();

// 参加者取得
$stmt = $db->prepare("SELECT events.name, events.detail, events.start_at, events.end_at, users.slack_id FROM events JOIN event_attendance ON event_attendance.event_id = events.id LEFT JOIN users ON event_attendance.user_id = users.id
WHERE '$tomorrow_start' < events.start_at 
AND events.start_at < '$tomorrow_end' 
AND event_attendance.status = 'presence'
");
$stmt->execute();
$participants = $stmt->fetchAll();


$array = [];
foreach ($participants as $participant) {
    $array[] = $participant['slack_id'];
}

$mentions = implode("><@",$array);

$name = $event['name'];
$detail = $event['detail'];
$start_at = $event['start_at'];
$end_at = $event['end_at'];
$slack_id = $event['slack_id'];

$url = 'https://hooks.slack.com/services/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';
$message = [
    "channel" => "#notify",
    "username" => "イベント通知管理ボット",
    "text" => "
    ------------------------------------------
    イベント【前日】です！楽しみにしていてください！

    ■ イベント名
    $name

    ■ 内容
    $detail

    ■ 開催日時
    $start_at ~ $end_at
    
    ■ 参加者一覧
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