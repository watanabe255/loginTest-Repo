<?php
session_start();
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.html');
    exit();
}
echo "ログイン成功！ あなたのユーザーID: " . htmlspecialchars($_SESSION['user_id']);
?>
