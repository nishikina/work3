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
<link rel="stylesheet" href="../css/a_contact_form.css">
<title>お問い合わせ</title>
</head>
<body>
<form id="myForm" class="validationForm" novalidate action="" method="post">
<h1>お問い合わせ情報</h1>
<div>
    <label for="name">*お名前 </label>
    <input class="required pattern" data-error-pattern="名前は必須です" type="text" name="name" id="name" placeholder="自走 太郎" autocomplete="off">
</div>
<div>
    <label for="tel"> 電話番号(任意) </label>
    <input class="pattern" data-pattern="tel" data-error-pattern="適切な桁数及び半角数字でご入力ください" type="tel" name="tel" id="tel" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}" placeholder="090-0000-0000" autocomplete="off">
</div>
<div>
    <label for="email1">*メールアドレス </label>
    <input class="required pattern" data-pattern="email" data-error-required="メールアドレスは必須です" data-error-pattern="メールアドレスには@やドメインが必要です" type="email" id="email1" name="email1" size="30" placeholder="info@sample.com" autocomplete="off">
</div>
<br>
<br>
<div>
    <label for="inquiry_type">*お問い合わせの種類</label><br>
    <select name="inquiry_type" id="inquiry_type" required>
        <option value="" disabled selected>選択してください</option>
        <option value="商品や販売店についてのお問い合わせ">商品や販売店についてのお問い合わせ</option>
        <option value="その他のお問い合わせ">その他のお問い合わせ</option>
    </select>
</div>
<div>
    <label for="subject">*件名</label><br>
    <input type="text" id="subject" name="subject" required>
</div>
<div>
    <label for="content">*お問い合わせ内容</label><br>
    <textarea id="content" name="content" rows="5" required></textarea>
</div>
<p>商品の不具合についてのお問い合わせは、正確にお調べさせていただくため、<br>当該品及び容器をお取り置きの上、ご連絡ください。</p>
<button type="submit" name="send">送信</button>
</form>
<form method="post">
<button type="submit" name="logout"><img src="..\item\logo.jpg" alt="Shop Logo" class="logo"></button>
</form>
<script src="../js/a_contact_form.js"></script>
</body>
</html>
