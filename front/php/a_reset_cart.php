<?php
session_start();

// セッション内のカートを空にする
$_SESSION['cart'] = array();

// リセット後、カートページにリダイレクトする
header('Location: a_index.php');
exit();
?>
