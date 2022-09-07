<?php
require('../dbconnect.php');
header('Content-Type: application/json; charset=UTF-8');

$eventId = $_POST['eventId'];
$userId = $_POST['userId'];
$status = $_POST['status'];

$stmt = $db->prepare('SELECT COUNT(user_id) FROM event_attendance WHERE user_id = ? AND event_id = ?');
$stmt->execute(array($userId, $eventId));
$number = $stmt->fetch();

// 重複している値がない場合は挿入、ある場合はステータス更新
if ($eventId > 0 && $number[0] == 0) {
  $stmt = $db->prepare('INSERT INTO event_attendance SET event_id=?, user_id = ?, status = ?');
  $stmt->execute(array($eventId, $userId, $status));
} else {
  $stmt = $db->prepare('UPDATE event_attendance SET status = ? WHERE event_id = ? AND user_id = ?');
  $stmt->execute(array($status, $eventId, $userId));
}
