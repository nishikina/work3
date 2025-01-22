<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Be.nature</title>
<link rel="stylesheet" href="../css/reset.css">
<link rel="stylesheet" href="../css/a_styles.css">
<link rel="stylesheet" href="../css/slick.css"/>
<link rel="stylesheet" href="../css/slick-theme.css"/>

<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="../js/slick.min.js"></script>
</head>
<body>

<header class="header">
  <ul class="header__logo">
    <li><a href="a_index.php"><img src="../item/logo.jpg" alt="logo"></a></li>
  </ul>  
  <ul class="header__navi">
    <li><a href="a_index.php">Shop</a></li>
    <li><a href="#access">Access</a></li>
    <li><a href="a_contact_form.php">Contact</a></li>
    <li><a href="a_cart.php"><img src="..\item\cart.png" alt="Cart"><span id="cartItemCount" class="cartItemCount">0</span></a></li>    
  </ul>
</header>

<section class="slogan">
  <h1>自然から食卓へいろどりを</h1>
</section>

<br>

<!-- モーダル -->
<div id="cartModal" class="modal">
    <!-- モーダルのコンテンツ -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="cart-title">お買い物カート</h2>
        <br>
        <br>
        <div class="cart-items">
            <!-- ここにJavaScriptでカートアイテムが追加されます -->
        </div>
    </div>
</div>
<!-- 商品詳細用モーダル -->
<div id="detailModal" class="product_modal">
    <!-- モーダルのコンテンツ -->
    <div class="product-modal-content">
        <span class="close">&times;</span>
        <div class="product-images"></div> <!-- 画像を横並びで表示するための要素 -->
        <div class="product-details">
            <h2 id="modalTitle">商品詳細</h2>
            <br>
            <br>
            <div id="productDescription"></div>
            <p id="productPrice"></p>
        </div>
    </div>
</div>


<!-- ページ内のカート表示は削除 -->

<form method="get">
    <select name="category">
        <option value="">カテゴリ</option>
        <option value="">全商品</option>
        <option value="1">発酵食品</option>
        <option value="2">有機野菜・果物</option>
        <option value="3">嗜好品</option>
    </select>

    <select name="price">
        <option value="">並び順</option>
        <option value="price1">価格の安い</option>
        <option value="price2">価格の高い</option>
    </select>

    <select name="stock">
        <option value="">在庫状況</option>
        <option value="stock_all">全商品</option>
        <option value="in_stock">在庫あり</option>
        <option value="out_of_stock">在庫なし</option>
    </select>

    <input type="submit" value="絞り込む">
</form>

<section id="products">
  <?php
  require_once 'connect.php';

  try {
      // 商品情報を取得して表示
      $sql = "SELECT p.*, COALESCE(i.stock_quantity, 0) AS stock_quantity FROM product p LEFT JOIN inventory i ON p.pr_id = i.pr_id";
      $stmt = $pdo->query($sql);

      if ($stmt->rowCount() > 0) {
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              // 商品の div 要素を開始します
              echo '<div class="product" data-product-id="' . $row["pr_id"] . '">';
              echo '<img src="' . $row["pr_img"] . '" alt="' . $row["pr_name"] . '" class="product-img" data-toggle="modal" data-target="#productModal">';
              echo '<h3>' . $row["pr_name"] . '</h3>';
              echo '<p>' . $row["pr_about"] . '</p>';
              echo '<p>価格(税込): ¥' . $row["pr_price"] . '</p>';
              if($row['stock_quantity'] == 0) {
                  echo '<p>SOLD OUT</p>';
              } else {
                  echo '<div class="add-to-cart">';
                  echo '<input type="number" value="0" min="0" class="quantity-input">'; // 個数を入力するためのinput要素
                  echo '<button class="add-to-cart-btn" data-pr-id="' . $row['pr_id'] . '" data-pr-name="' . $row['pr_name'] . '" data-pr-price="' . $row['pr_price'] . '" data-stock-quantity="' . $row['stock_quantity'] . '">カートに追加</button>';
                  echo '</div>';
              }
              // 商品の div 要素を終了します
              echo '</div>';
          }
      } else {
          echo "商品がありません";
      }
  } catch(PDOException $e) {
      echo "データベース接続エラー: " . $e->getMessage();
  }
  ?>
</section>


<section class="access" id="access">
  <h2 class="access__title">Access</h2>
  <div class="access__map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3280.9293874785076!2d135.544083375205!3d34.68173158423129!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6909ee25f862dff9%3A0x4250996b876e24f2!2z6IG35qWt6KiT57e05qCh44CM44K444K944Km44K544Kk44OD44OB44CN!5e0!3m2!1sja!2sjp!4v1713789172859!5m2!1sja!2sjp" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
  </div>
  <div class="access__detail">
    <dl>
      <dt>住所</dt>
      <dd>〒536-0023 大阪市城東区東中浜3丁目15-10 トラストリング株式会社内</dd>
    </dl>
    <dl>
      <dt>アクセス</dt>
      <dd>
      <div>地下鉄大阪メトロ 中央線‧今里筋線「緑橋」7番出口 徒歩3分</div>
      </dd>
    </dl>
    <dl>
      <dt>営業時間</dt>
      <dd>平日：10:00〜16:50</dd>
    </dl>
    <dl>
      <dt>定休日</dt>
      <dd>土曜・日曜・祝日</dd>
    </dl>
  </div>
</section>

<footer class="footer">
  <div class="footer__inner">
    <div class="footer__inner-sns">
      <ul>
        <li><a href="#"><img src="../item/sns01.png" alt=""></a></li>
        <li><a href="#"><img src="../item/sns02.png" alt=""></a></li>
      </ul>
      <div class="copyright">© 2024 B team</div>
    </div>
  </div>
</footer>

<script src="../js/a_add-to-cart.js"></script>
<script src="../js/a_update-cart.js"></script>
<script src="../js/a_update-cart-icon.js"></script>
<script src="../js/a_reset_cart.js"></script>
<script src="../js/a_cart_modal.js"></script>
<script src="../js/a_filter.js"></script>
<script src="../js/a_product-modal.js"></script>
<script>
$('.slider').slick({
  dots: true,
  infinite: true,
  speed: 500,
  fade: true,
  cssEase: 'linear'
});
</script>
</body>
</html>
