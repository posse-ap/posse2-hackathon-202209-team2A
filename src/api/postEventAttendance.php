<?php
require('../dbconnect.php');
header('Content-Type: application/json; charset=UTF-8');

$eventId = $_POST['eventId'];
$userId = $_POST['userId'];
$status = $_POST['status'];

$sql = 'SELECT COUNT(user_id) FROM event_attendance WHERE user_id = ? AND event_id = ?';
$stmt = $db->prepare($sql);
$stmt->execute(array($userId, $eventId));
$number = $stmt->fetch();


if ($eventId > 0 && $number = 0) {
  $stmt = $db->prepare('INSERT INTO event_attendance SET event_id=?, user_id = ?, status = ?');
  $stmt->execute(array($eventId, $userId, $status));
} else {
  $stmt = $db->prepare('UPDATE event_attendance SET user_id = ?, status = ? WHERE event_id = ?');
  $stmt->execute(array($userId, $status, $eventId));
}
