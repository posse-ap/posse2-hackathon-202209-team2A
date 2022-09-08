

<?php
require('dbconnect.php');

date_default_timezone_set('Asia/Tokyo');
mb_language('ja');
mb_internal_encoding('UTF-8');

// 明日の始まりと終わり
$tomorrow_start  = date('Y-m-d 00:00:00', strtotime("+3 day"));
$tomorrow_end  = date('Y-m-d 23:59:59', strtotime("+3 day"));

// SELECT events.name, events.start_at, users.name,event_attendance.status FROM events LEFT JOIN event_attendance ON event_attendance.event_id = events.id LEFT JOIN users ON event_attendance.user_id = users.id WHERE events.start_at >= CURDATE() AND event_attendance.status='presence' ORDER BY events.name DESC;
$stmt = $db->prepare("SELECT *
    FROM events 
    CROSS JOIN users 
    WHERE events.start_at >= CURDATE() 
    AND NOT EXISTS (
     select * from event_attendance 
    where event_attendance.user_id = users.id 
    and event_attendance.event_id = events.id) 
    and '$tomorrow_start' < start_at AND start_at < '$tomorrow_end';
");

$stmt->execute();
$participants = $stmt->fetchAll();

foreach ($participants as $participant) {

    $to = $participant['email'];
    $user_name = $participant['name'];
    $event_detail = $tomorrow_event['detail'];


    $tomorrow_event_name = $participant[1];
    $subject = <<<EOT
            『${tomorrow_event_name}』リマインドメール（３日前 @未回答者）
            EOT;
    $body = "本文";
    $headers = ["From" => "system@posse-ap.com", "Content-Type" => "text/plain; charset=UTF-8", "Content-Transfer-Encoding" => "8bit"];

    $participant_name = $participant['name'];
    $three_days_later_date = $participant['start_at'];
    $event_detail = $participant['detail'];

    $body = <<<EOT
    {$participant_name}さん

    このメールはイベントアプリの未回答者にのみ送信しています。
    参加予定の「${tomorrow_event_name}」の３日前となりました。
    ${three_days_later_date}にイベントが始まります！


    明日の ${event_date}より『${tomorrow_event_name}』を開催いたします！
    {$user_name}さん、ぜひ参加してくださいね！

    【イベント詳細】

    {$event_detail}

    EOT;

    mb_send_mail($to, $subject, $body, $headers);
}

echo "メールを送信しました";
