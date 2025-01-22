<?php
session_start();
require_once "db_connect.php"; // データベース接続ファイルをインクルード

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたユーザー名とパスワードを取得
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // 入力されたユーザー名を使用してadminsテーブルからユーザーを検索
        $stmt = $db->prepare("SELECT id, username, password FROM admins WHERE username=:username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // ユーザーが見つかった場合、パスワードを検証
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['password'] == $password) {
                // ログイン成功
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                // ログイン成功の場合は、JavaScriptを使用してリダイレクト
                echo "<script>window.location.replace('back_main.php');</script>";
                exit();
            } else {
                // パスワードが一致しない場合、JavaScriptのalertを使ってアラート表示
                echo "<script>alert('パスワードが正しくありません。');</script>";
            }
        } else {
            // ユーザーが見つからない場合、JavaScriptのalertを使ってアラート表示
            echo "<script>alert('ユーザーが見つかりません。');</script>";
        }
    } catch(PDOException $e) {
        // エラー処理
        echo "エラー: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../css/b_main.css">

<title>ログインページ</title>
</head>
<body>
<div class="loginform">
<h1>Be.nature</h1>
<img src="../../img/logo.jpg">
<h1>管理画面ログイン</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="login">
<div class="loginlabel">
    <label for="username" class="loginlabel">ユーザー名</label><br>
    <input type="text" id="username" name="username">
  </div>
  <div class="loginlabel">
    <label for="password" class="loginlabel">パスワード</label><br>
    <input type="password" id="password" name="password">
  </div>
  <div>
    <input type="submit" value="ログイン" class="loginbtn">
  </div>
  </div>  
</form>
</div>
</body>
</html>
