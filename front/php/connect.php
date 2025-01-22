<?php
// connect.php

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=$charset", $db_username, $password);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // 接続エラーの場合、エラーメッセージを表示してスクリプトを終了
    exit('データベースに接続できませんでした: ' . $e->getMessage());
}
?>

