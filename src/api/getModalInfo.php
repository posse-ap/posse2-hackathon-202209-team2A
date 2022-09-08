<?php
session_start();
require('../dbconnect.php');
header('Content-Type: application/json; charset=UTF-8');

if (isset($_GET['eventId'])) {
  $eventId = htmlspecialchars($_GET['eventId']);
  $userId = $_SESSION['user_id'];
  try {
    $stmt = $db->prepare('SELECT events.id, events.name, events.start_at, events.end_at FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE events.id = ? GROUP BY events.id');
    $stmt->execute(array($eventId));
    $event = $stmt->fetch();
    
    $start_date = strtotime($event['start_at']);
    $end_date = strtotime($event['end_at']);

    if (isset($event['detail'])) 
    {
      $eventMessage = $event['detail'];
    } else {
      $eventMessage = '詳細はありません';
    }

    if ($event['id'] % 3 === 1) $status = 0;
    elseif ($event['id'] % 3 === 2) $status = 1;
    else $status = 2;

    // 参加状況
    $stmt = $db->prepare('SELECT user_id, status FROM event_attendance WHERE event_id = ? AND user_id = ?');
    $stmt->execute(array($eventId, $userId));
    $participation_status = $stmt->fetch();

    // 参加者の合計を求める
    $stmt = $db->prepare("SELECT COUNT(user_id) FROM event_attendance WHERE event_id = ? AND status = 'presence'");
    $stmt->execute(array($event['id']));
    $participants_total = $stmt->fetch();

    // 参加者の情報を取得
    $stmt = $db->prepare("SELECT users.name FROM users INNER JOIN event_attendance ON users.id = event_attendance.user_id WHERE event_id = ? AND event_attendance.status = 'presence'");
    $stmt->execute(array($eventId));
    $participant_names = $stmt->fetchAll();


    $array = [
      'id' => $event['id'],
      'name' => $event['name'],
      'date' => date("Y年m月d日", $start_date),
      'day_of_week' => get_day_of_week(date("w", $start_date)),
      'start_at' => date("H:i", $start_date),
      'end_at' => date("H:i", $end_date),
      'total_participants' => $participants_total[0],
      'message' => $eventMessage,
      'status' => $status,
      'participation_status' => $participation_status['status'],
      'participant_names' => $participant_names,
      'deadline' => date("m月d日 H:i:s", strtotime('-3 day', $end_date)),
    ];
    
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
  } catch(PDOException $e) {
    echo $e->getMessage();
    exit();
  }
}

function get_day_of_week ($w) {
  $day_of_week_list = ['日', '月', '火', '水', '木', '金', '土'];
  return $day_of_week_list["$w"];
}