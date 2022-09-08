

<?php
require('dbconnect.php');

date_default_timezone_set('Asia/Tokyo');
mb_language('ja');
mb_internal_encoding('UTF-8');

// ３日後の始まりと終わりを取得
$three_days_later_start  = date('Y-m-d 00:00:00', strtotime("+1 day"));
$three_days_later_end  = date('Y-m-d 23:59:59', strtotime("+1 day"));

// ３日後中に行われるイベントのみを取得 同時にuserにおいて、statusがpresence（参加）となってる人のみ取得
$stmt = $db->prepare("SELECT * FROM events JOIN event_attendance ON event_attendance.event_id = events.id LEFT JOIN users ON event_attendance.user_id = users.id
WHERE '$three_days_later_start' < events.start_at 
AND events.start_at < '$three_days_later_end' 
AND event_attendance.status = 'presence'
");
$stmt->execute();
$participants = $stmt->fetchAll();

foreach ($participants as $participant) {

    var_dump($participant[1]);

    $to = $participant['email'];
    $user_name = $participant['name'];
    $three_days_later_event_name = $participant[1];
    $subject = <<<EOT
            『${three_days_later_event_name}』リマインドメール（前日 @参加者）
            EOT;
    $body = "本文";
    $headers = ["From" => "system@posse-ap.com", "Content-Type" => "text/plain; charset=UTF-8", "Content-Transfer-Encoding" => "8bit"];

    $participant_name = $participant['name'];
    $three_days_later_date = $participant['start_at'];
    $detail = $participant['detail'];
    $body = <<<EOT
    {$participant_name}さん
    参加予定の「${three_days_later_event_name}」の前日となりました。
    ${three_days_later_date}に始まります！

    参加予定の方にのみ連絡を差し上げています。
    明日の ${event_date}より『${three_days_later_event_name}』を開催いたします！
    {$user_name}さんのご参加、楽しみにしています！

    【イベント詳細】

    EOT;

    mb_send_mail($to, $subject, $body, $headers);
}

echo "メールを送信しました";
