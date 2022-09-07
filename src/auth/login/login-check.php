<?php
// ログイン済みかを確認
if (!isset($_SESSION['email'])) {
    header('Location: http://localhost/auth/login/index.php');
    exit();
  }