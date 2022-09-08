

<?php
require('dbconnect.php');

date_default_timezone_set('Asia/Tokyo');
mb_language('ja');
mb_internal_encoding('UTF-8');

// 明日の始まりと終わり
$tomorrow_start  = date('Y-m-d 00:00:00', strtotime("+3 day"));
$tomorrow_end  = date('Y-m-d 23:59:59', strtotime("+3 day"));

// ３日後中に行われるイベントのみを取得 同時にuserにおいて、statusがpresence（参加）となってる人のみ取得
$stmt = $db->prepare("SELECT events.id, events.name, events.start_at, users.name 
FROM events 
CROSS JOIN users 
WHERE events.start_at >= CURDATE() 
AND NOT EXISTS (
     select * from event_attendance 
    where event_attendance.user_id = users.id 
    and event_attendance.event_id = events.id) 
    ORDER BY 'event.name' ASC;
");



$stmt->execute();
$participants = $stmt->fetchAll();

foreach ($participants as $participant) {

    var_dump($participant);

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
    参加予定の「${tomorrow_event_name}」の前日となりました。
    ${three_days_later_date}に始まります！

    参加予定の方にのみ連絡を差し上げています。
    明日の ${event_date}より『${tomorrow_event_name}』を開催いたします！
    {$user_name}さんのご参加、楽しみにしています！

    【イベント詳細】

    {$event_detail}

    EOT;

    mb_send_mail($to, $subject, $body, $headers);
}

echo "メールを送信しました";
