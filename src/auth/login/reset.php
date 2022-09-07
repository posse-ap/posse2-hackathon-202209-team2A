<?php
session_start();
require('../../dbconnect.php');

$email = $_SESSION['email'];

if (isset($_POST['reset'])) {
  $password = sha1($_POST['password']);

  $sql = 'UPDATE users
            SET password = ?
            WHERE email = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($password, $email));
  $stmt = null;
  $db = null;

  header('Location: http://localhost/auth/login/reset_done.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>パスワードリセット</title>
</head>

<body>
  <div class="util_fullscreen_container">
    <div class="util_fullscreen reset">
      <h1 class="util_login_title">パスワード再発行</h1>
      <form action="/auth/login/reset.php" method="POST">
        <p class="reset_text">メールアドレス：<?= $email ?></p>
        <div class="util_login_text reset_input">
          <label class="util_login_text--label" for="password">パスワード：</label>
          <input class="util_login_text--box" type="password" name="password" id="password" required>
          <i class="fas fa-eye-slash" id="togglePassword"></i>
        </div>
        <div class="util_login_text reset_input">
          <input type="submit" name="reset" value="リセット" class="util_login_button">
        </div>
      </form>
    </div>
  </div>




</body>


