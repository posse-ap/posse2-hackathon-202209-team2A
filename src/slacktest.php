<?php 
require('dbconnect.php');

date_default_timezone_set('Asia/Tokyo');
mb_language('ja');
mb_internal_encoding('UTF-8');

// 明日の始まりと終わり
$tomorrow_start  = date('Y-m-d 00:00:00', strtotime("+1 day"));
$tomorrow_end  = date('Y-m-d 23:59:59', strtotime("+1 day"));

// ３日後中に行われるイベントのみを取得 同時にuserにおいて、statusがpresence（参加）となってる人のみ取得
$stmt = $db->prepare("SELECT * FROM events JOIN event_attendance ON event_attendance.event_id = events.id LEFT JOIN users ON event_attendance.user_id = users.id
WHERE '$tomorrow_start' < events.start_at 
AND events.start_at < '$tomorrow_end' 
AND event_attendance.status = 'presence'
");
$stmt->execute();
$participants = $stmt->fetchAll();

$url = 'https://hooks.slack.com/services/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';
$message = [
    "channel" => "#notify",
    "username" => "イベント通知管理ボット",
    "text" => "
    イベント【前日】です！楽しみにしていてください！

    ■ イベント名

    ■ 内容

    ■ 開催日時
    
    ■ 参加者一覧",
];

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