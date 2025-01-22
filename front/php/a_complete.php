<?php
// セッションの開始
session_start();

// もしボタンが押されたら
if (isset($_POST['logout'])) {
    // セッションを破棄
    session_destroy();
    // トップページにリダイレクト
    header("Location: http://localhost/Shop_TeamB/front/php/a_index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>購入ありがとうございました</title>
<link rel="stylesheet" href="../css/a_complete.css"> <!-- 必要に応じてスタイルシートを読み込む -->
</head>
<body>

<div class="container">
  <h1>購入ありがとうございました</h1>
  <p>ご購入いただき、誠にありがとうございます。</p>
  <p>ご注文内容を確認いたしました。</p>
  <p>商品は指定された住所に発送いたしますので、お待ちください。</p>
  <p>ご不明点やお問い合わせがございましたら、お気軽にお問い合わせください。</p>
  <p><a href="a_contact_form.php">お問い合わせ</a></p>
  <form method="post">
    <!-- ボタンが押されたことをサーバーに伝える -->
    <button type="submit" name="logout" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">TOPに戻る</button>
  </form>
</div>

<form method="post">
<button type="submit" name="logout"><img src="..\item\logo.jpg" alt="Shop Logo" class="logo"></button>
  </form>
  
</body>
</html>
