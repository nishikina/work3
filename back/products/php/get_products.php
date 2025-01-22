<?php
include 'db_connect.php';

try {
    $stmt = $db->query("SELECT product.pr_id, product.pr_name, product.pr_price, product.pr_about, product.pr_img, category.ct_name, inventory.stock_quantity
                        FROM product
                        INNER JOIN category ON product.ct_id = category.ct_id
                        INNER JOIN inventory ON product.pr_id = inventory.pr_id");

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
} catch(PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>
