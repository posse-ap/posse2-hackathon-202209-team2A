<?php

session_start();
require('../../dbconnect.php');


$err_msg = "";


if (isset($_POST['submit_email'])) {
  $_SESSION["email"] = $_POST['email'];
  $email = $_POST['email'];

  $sql = 'SELECT count(*) FROM users WHERE email = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($email));
  $result = $stmt->fetch();

  // result に一つでも値が入っているなら、登録メールアドレスが存在するということ
  if ($result[0] != 0) {

    $passResetToken = md5(uniqid(rand(), true));

    // DB に email と token を追加
    $sql = "INSERT INTO user_password_reset(email, pass_token) VALUES(?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($email, $passResetToken));

    // メール送信 
    $to      = $_POST['email'];
    $subject = "パスワード再発行";
    $message = "
    ログインのパスワードリセットの申請を受け付けました。
    パスワードの再設定をご希望の場合は、以下URLをクリックし
    新しいパスワードをご登録ください。
    パスワードリセットの申請に心当たりがない場合は、以降の対応は不要となります。
    パスワードの再設定URL：
    http://localhost/auth/login/reset.php?pass_reset=${$passResetToken}
    ";
    $headers = ["From" => "posse-ap", "Content-Type" => "text/plain; charset=UTF-8", "Content-Transfer-Encoding" => "8bit"];;

    mb_send_mail($to, $subject, $message, $headers);

    header('Location: http://localhost/auth/login/send_link.php');

    exit;
  } else {
    $err_msg = "メールアドレスが登録されていません。";
  }
}






?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>パスワード再発行</title>
</head>

<body>
  <div class="util_fullscreen_container">
    <div class="util_fullscreen util_login">
      <h1 class="util_login_title">パスワード再発行</h1>
      <div class="forget">
        <p class="forget_text">パスワードの再設定が必要となります。</p>
        <p class="forget_text">恐れ入りますが、登録されたメールアドレスをご入力いただき、受信されたメールの案内に従ってパスワード再設定をお願いします。</p>
        <br><br><br>
        <p class="forget_text">登録しているメールアドレス</p>
        <?php if ($err_msg !== null && $err_msg !== '') {
          echo "<p class='util_login_error'>" . $err_msg .  "</p>";
        } ?>
      </div>
      <form action="forget.php" method="POST">
        <input class="util_login_text--box" type="email" name="email" required>
        <br><br>
        <input type="submit" name="submit_email" class="util_login_button">
      </form>
    </div>
  </div>


</body>

</html>