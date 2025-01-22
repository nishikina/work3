<?php
$host = 'localhost';
$dbname = 'qol_shop';
$username = 'root';
$password = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '' : 'root';

try {
    if ($username === 'root' && $password === '') {
        // ルートユーザーでパスワードが空の場合はパスワードなしで接続
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username);
    } else {
        // それ以外の場合は指定したユーザー名とパスワードで接続
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

?>
