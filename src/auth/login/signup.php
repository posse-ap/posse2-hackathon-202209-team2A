<?php
session_start();
require('../../dbconnect.php');

$err_msg = "";


if (isset($_POST['signup'])) {
  $email = $_POST['email'];
  $password = sha1($_POST['password']);
    $sql = 'INSERT INTO users(email, password)
          VALUES(?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($email, $password));
    $stmt = null;
    $db = null;
    header('Location: http://localhost/auth/login/signup_done.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー新規登録</title>
</head>

<body>
  <div class="util_fullscreen_container">
    <div class="util_fullscreen util_login">
      <h1 class="util_login_title">ユーザー新規登録</h1>
      <?php if ($err_msg !== null && $err_msg !== '') {
          echo "<p class='util_login_error'>" . $err_msg .  "</p>";
      } ?>
      <form action="" method="POST">
        <div class="signup">
          <label class="util_login_text--label" for="email">メールアドレス：</label>
          <input class="util_login_text--box" type="email" name="email" required>
        </div>
        <div class="signup">
          <label class="util_login_text--label" for="password">パスワード：</label>
          <input class="util_login_text--box" type="password" name="password" id="password" required>
        </div>
        <div>
          <input type="submit" name="signup" value="新規登録" class="util_login_button">
        </div>
      </form>
    </div>
  </div>
</body>
</html>