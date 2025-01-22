<?php
session_start();

// カートの内容を復元する
if (!empty($_POST['cart_contents'])) {
    $cart_contents = json_decode($_POST['cart_contents'], true);
    // カートの内容をセッションに保存
    $_SESSION['cart'] = $cart_contents;
}

// カートの情報を取得
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

// カートの合計金額を計算
$total_cart_price = 0;
foreach ($cart_items as $item) {
    $total_cart_price += $item['total_price'];
}
// ここからは、購入者情報とカートの内容をデータベースに送信する処理を実行します
// この部分は、データベースの接続やデータの挿入など、あなたのアプリケーションに合わせてカスタマイズする必要があります
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/a_order.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    <title>ご購入情報</title>
</head>
<body>
    <!-- form 要素に class="validationForm" と novalidate 属性を指定 -->
  <form id="myForm" class="validationForm" novalidate action="a_purchase_handler.php" method="post">
  <h1>ご購入情報</h1>

  <!-- カートの情報を表示 -->
  <h3>ご購入商品内訳</h3>
  <table border="1" style="margin-left: 100px;">
        <tr>
            <th>商品名</th>
            <th>単価(税込)</th>
            <th>数量</th>
            <th>合計金額(税込)</th>
        </tr>
        <?php foreach ($cart_items as $item) : ?>
            <tr>
                <td><?= $item['pr_name'] ?></td>
                <td><?= $item['product_price'] ?>円</td>
                <td><?= $item['quantity'] ?></td>
                <td><?= $item['total_price'] ?>円</td>                
            </tr>
        <?php endforeach; ?>
    </table>

     <!-- カート内の合計金額を表示 -->
<div style="margin-left: 400px;">
    <h4>カートの合計金額(税込): <?= $total_cart_price ?>円</h4>
</div>

    
    <!--  div 要素でコントロールとラベルを囲む -->
    <div>
        <label for="name">*お名前 </label>
        <!-- 検証用クラスや属性をコントロール要素に指定 -->
        <input class="required pattern" data-error-pattern="名前は必須です" type="text" name="name" id="name" 
        placeholder="自走 太郎" autocomplete="off">
    </div>
    <div>
      <label for="tel">*電話番号 </label>
      <input class="required pattern" data-pattern="tel" data-error-pattern="適切な桁数及び半角数字でご入力ください" 
      type="tel" name="tel" id="tel" pattern="[0-9]{3}-[0-9]{4}-[0-9]{4}"placeholder="090-0000-0000" autocomplete="off">
    </div>
    <div>
        <label for="zip">*郵便番号</label>
        <input class="required pattern" type="text" name="zip" id="zip" pattern="[0-9]{3}-[0-9]{4}" 
        data-pattern="zip" data-error-pattern="適切な桁数及び半角数字でご入力ください" 
        size="8" maxlength="8" placeholder="111-1111" autocomplete="off">      
        <button type="button" class="ajaxzip3" href="#">住所を取得</button>
    </div>
    <div>
        <label for="pref">*都道府県</label>
        <input class="required pattern" type="text" id="pref" name="pref" placeholder="○○県"
        data-error-pattern="都道府県は必須です" autocomplete="off">
    </div>
    <div>
        <label for="addr1">*市区町村</label>
        <input class="required pattern" type="text" id="addr1" name="addr1" placeholder="○○市"
        data-error-pattern="市区町村は必須です" autocomplete="off">
    </div>
    <div>
        <label for="addr2">*町域・番地・建物名など</label>
        <input class="required pattern" name="addr2" id="addr2" type="text" placeholder="○○町○番地"
        data-error-pattern="町域・番地は必須です" autocomplete="off">
    </div>
    <div>
      <label for="email1">*メールアドレス </label>
      <input class="required pattern" data-pattern="email" data-error-required="メールアドレスは必須です" 
      data-error-pattern="メールアドレスには@やドメインが必要です" type="email" id="email1" name="email1" size="30"
      placeholder="info@sample.com" autocomplete="off">
    </div>
    <div>
      <p>*お支払方法</p>
      <input class="required" data-error-required="いずれかを選択してください" type="radio" name="payment_method" value="bank" id="bank">
      <label for="bank"> 銀行振込 </label>
      <input class="required" type="radio" name="payment_method" value="cod" id="cod">
      <label for="cod"> 代金引換 </label>
      <!-- エラーメッセージの表示領域を追加 -->
      <span id="radioErrorMessage" class="error-message"></span>
    </div>
    <br>
    
    <button type="submit" name="send">購入を確定</button>
    

    <a href="a_index.php"><img src="..\item\logo.jpg" alt="Shop Logo" class="form-logo"></a>

  </form>

  <script src="../js/a_order.js"></script>
  
</body>
</html>
