<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../css/b_main.css">
<title>商品一覧</title>
</head>
<body>
<div class="b_pr_top">
<div class="b_tittle"><h2>Be.nature 商品一覧</h2>
<a href="../../back_main/php/back_main.php">管理画面へ戻る</a></div>
<div class="pr_tittle">
<div id="lastUpdated"></div>
  <div class="b_user">
<?php
if(isset($_SESSION['username'])) {
    echo "<div>ログイン中：{$_SESSION['username']} さん</div>";
} else {
    echo "<div>ログインしていません</div>";
}
?></div>

</div>

<div class="container">
<!-- 検索フォーム -->
<div class="sarchform">
<form method="GET" action="">
<label for="category">カテゴリー</label><br>
<select id="category" name="category">
  <option value="">すべて</option>
  <option value="hakkou" <?php if (isset($_GET['category']) && $_GET['category'] === 'hakkou') echo 'selected'; ?>>hakkou</option>
  <option value="organic" <?php if (isset($_GET['category']) && $_GET['category'] === 'organic') echo 'selected'; ?>>organic</option>
  <option value="indulgence" <?php if (isset($_GET['category']) && $_GET['category'] === 'indulgence') echo 'selected'; ?>>indulgence</option>
</select><br>

  <label for="product_id">商品ID</label><br>
  <input type="text" id="product_id" name="product_id" placeholder="一部一致OK"><br>
  
  <label for="product_name">商品名</label><br>
  <input type="text" id="product_name" name="product_name" placeholder="一部一致OK"><br>
  
  <label for="price">商品単価</label><br>
<select id="price" name="price">
  <option value="">すべて</option>
  <option value="3000以下" <?php if (isset($_GET['price']) && $_GET['price'] === '3000以下') echo 'selected'; ?>>3000円以下</option>
  <option value="5000以下" <?php if (isset($_GET['price']) && $_GET['price'] === '5000以下') echo 'selected'; ?>>5000円以下</option>
  <option value="10000以下" <?php if (isset($_GET['price']) && $_GET['price'] === '10000以下') echo 'selected'; ?>>10000円以下</option>
  <option value="それ以上" <?php if (isset($_GET['price']) && $_GET['price'] === 'それ以上') echo 'selected'; ?>>それ以上</option>
</select><br>

<label for="stock_quantity">在庫数</label><br>
<input type="text" id="stock_quantity" name="stock_quantity" value="<?php echo isset($_GET['stock_quantity']) ? htmlspecialchars($_GET['stock_quantity']) : ''; ?>" placeholder="整数を入力">

<label for="below">以下</label>
<input type="checkbox" id="below" name="below" <?php if (isset($_GET['below']) && $_GET['below'] === 'on') echo 'checked'; ?>>
<label for="above">以上</label>
<input type="checkbox" id="above" name="above" <?php if (isset($_GET['above']) && $_GET['above'] === 'on') echo 'checked'; ?>><br>
 <input type="submit" value="検索" class="sarchbtn">
</form></div>
<div class="pr_table">
<table border="1">
  <thead>
    <tr>
      <th>商品ID</th>
      <th>商品名</th>
      <th>価格</th>
      <th>カテゴリ</th>
      <th>在庫数</th>
    </tr>
  </thead>
  <tbody id="productTableBody">
    <!-- 商品情報がここに表示 -->
    <?php
    include 'db_connect.php';
    
    try {
        // クエリの基本部分
        $query = "SELECT product.pr_id, product.pr_name, product.pr_price, product.pr_about, product.pr_img, category.ct_name, inventory.stock_quantity
                  FROM product
                  INNER JOIN category ON product.ct_id = category.ct_id
                  INNER JOIN inventory ON product.pr_id = inventory.pr_id";
    
        // 検索条件を受け取る
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
        $product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';
        $price = isset($_GET['price']) ? $_GET['price'] : '';
        $stock_quantity = isset($_GET['stock_quantity']) ? $_GET['stock_quantity'] : '';
        $below = isset($_GET['below']) && $_GET['below'] === 'on';
        $above = isset($_GET['above']) && $_GET['above'] === 'on';

        // 各検索条件をクエリに追加
        if (!empty($category)) {
            $query .= " WHERE category.ct_name = :category";
        }
        if (!empty($product_id)) {
            $query .= " AND product.pr_id LIKE :product_id";
        }
        if (!empty($product_name)) {
            $query .= " AND product.pr_name LIKE :product_name";
        }
        if (!empty($price)) {
            switch ($price) {
                case '3000以下':
                    $query .= " AND product.pr_price <= 3000";
                    break;
                case '5000以下':
                    $query .= " AND product.pr_price <= 5000";
                    break;
                case '10000以下':
                    $query .= " AND product.pr_price <= 10000";
                    break;
                case 'それ以上':
                    $query .= " AND product.pr_price > 10000";
                    break;
            }
        }
        if (!empty($stock_quantity)) {
          if ($below) {
              $query .= " AND inventory.stock_quantity <= :stock_quantity";
          }
          if ($above) {
              $query .= " AND inventory.stock_quantity >= :stock_quantity";
          }
      }
      
    
        // クエリの実行
        $stmt = $db->prepare($query);
    
        // プレースホルダに値をバインド
        if (!empty($category)) {
            $stmt->bindValue(':category', $category);
        }
        if (!empty($product_id)) {
            $stmt->bindValue(':product_id', '%' . $product_id . '%');
        }
        if (!empty($product_name)) {
            $stmt->bindValue(':product_name', '%' . $product_name . '%');
        }
        if (!empty($stock_quantity)) {
          $stmt->bindValue(':stock_quantity', $stock_quantity);
      }
    
        // クエリの実行
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td><a href='#' onclick='showProductDetails({$product['pr_id']})'>{$product['pr_id']}</a></td>";
            echo "<td>{$product['pr_name']}</td>";
            echo "<td>{$product['pr_price']}</td>";
            echo "<td>{$product['ct_name']}</td>";
            echo "<td id='stock_quantity_{$product['pr_id']}'>{$product['stock_quantity']}</td>";
           
            echo "</tr>";
        }
    } catch(PDOException $e) {
        echo "<tr><td colspan='6'>エラー: " . $e->getMessage() . "</td></tr>";
    }
    ?>
  </tbody>
</table>
<!-- モーダルウィンドウ1 -->
<div id="modal" class="modal">
  <div class="modal-content" id="modal-content">
    <!-- 商品の詳細情報がここに表示 -->
  </div>
</div>

<!-- モーダルウィンドウ2 -->
<div id="modal2" class="modal">
  <div class="modal-content" id="modal-content2">
<!-- 入出庫履歴がここに表示 -->

  </div>
</div>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../js/products.js"></script>
</body>
</html>
