<?php
session_start();
if(!isset($_SESSION['login']) || !$_SESSION['login']) {
    header('Location: login.html');
    exit(); // 重要！リダイレクト後の処理を防ぐ
}
?>
<html>
<body>
    ログイン成功！
</body>
</html>