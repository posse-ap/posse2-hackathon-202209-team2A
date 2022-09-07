<?php
require('../dbconnect.php');
header('Content-Type: application/json; charset=UTF-8');

$eventId = $_POST['eventId'];
$userId = $_POST['userId'];

if ($eventId > 0) {
  #12 event_id とともに user_id を入力。後から user_id に動的な値を入れたい
  $stmt = $db->prepare('INSERT INTO event_attendance SET event_id=?, user_id = ?');
  $stmt->execute(array($eventId, $userId));
}
