<?php
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $price = isset($_GET['price']) ? $_GET['price'] : '';
    $stock = isset($_GET['stock']) ? $_GET['stock'] : '';

    // カテゴリーの絞り込み条件を設定
    $categoryCondition = "";
    if (!empty($category) && $category != "category1") {
        $categoryCondition = "p.ct_id = '" . $category . "'";
    }

    // 価格の絞り込み条件とソート条件を設定
    $priceSort = "";
    if (!empty($price)) {
        if ($price == "price1") {
            // 安い順が選択された場合
            $priceSort = "ORDER BY p.pr_price ASC";
        } elseif ($price == "price2") {
            // 高い順が選択された場合
            $priceSort = "ORDER BY p.pr_price DESC";
        }
    }

    // 在庫の絞り込み条件を設定
    $stockCondition = "";
    if ($stock == "in_stock") {
        // 在庫がある場合
        $stockCondition = "i.stock_quantity > 0";
    } elseif ($stock == "out_of_stock") {
        // 在庫がない場合
        $stockCondition = "i.stock_quantity = 0";
    }

    // WHERE句を組み立てる
    $whereConditions = [];
    if (!empty($categoryCondition)) {
        $whereConditions[] = $categoryCondition;
    }
    if (!empty($stockCondition)) {
        $whereConditions[] = $stockCondition;
    }

    $whereClause = "";
    if (!empty($whereConditions)) {
        $whereClause = "WHERE " . implode(" AND ", $whereConditions);
    }

    // カテゴリーが全商品以外の場合には絞り込み条件を追加する
    if ($category != "category1") {
        $sql = "SELECT p.*, COALESCE(i.stock_quantity, 0) AS stock_quantity FROM product p LEFT JOIN inventory i ON p.pr_id = i.pr_id " . $whereClause;
    } else {
        $sql = "SELECT p.*, COALESCE(i.stock_quantity, 0) AS stock_quantity FROM product p LEFT JOIN inventory i ON p.pr_id = i.pr_id";
    }

    // 価格のソート条件を追加
    $sql .= " " . $priceSort;

    // 絞り込み後の商品を再表示
    displayFilteredProducts($pdo, $sql);
}

// 絞り込み条件を受け取り、絞り込んだ商品を再表示する関数
function displayFilteredProducts($pdo, $sql) {
    try {
        $stmt = $pdo->query($sql);

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // 商品の表示
                echo '<div class="product" data-product-id="' . $row["pr_id"] . '">';
                echo '<img src="' . $row["pr_img"] . '" alt="' . $row["pr_name"] . '" class="product-img" data-toggle="modal" data-target="#productModal">';
                echo '<h3>' . $row["pr_name"] . '</h3>';
                echo '<p>' . $row["pr_about"] . '</p>';
                echo '<p>価格: ¥' . $row["pr_price"] . '</p>';
                if($row['stock_quantity'] == 0) {
                    echo '<p>SOLD OUT</p>';
                } else {
                    echo '<div class="add-to-cart">';
                    echo '<input type="number" value="0" min="0" class="quantity-input">'; // 個数を入力するためのinput要素
                    echo '<button class="add-to-cart-btn" data-pr-id="' . $row['pr_id'] . '" data-pr-name="' . $row['pr_name'] . '" data-pr-price="' . $row['pr_price'] . '" data-stock-quantity="' . $row['stock_quantity'] . '">カートに追加</button>';
                    echo '</div>';
                }
                echo '</div>';
            }
        } else {
            echo "絞り込み条件に該当する商品はありません";
        }
    } catch(PDOException $e) {
        echo "データベース接続エラー: " . $e->getMessage();
    }
}
?>
