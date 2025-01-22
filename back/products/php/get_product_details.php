<?php
include 'db_connect.php';

if(isset($_GET['pr_id'])) {
    $pr_id = $_GET['pr_id'];
    try {
        $stmt = $db->prepare("SELECT product.pr_id, product.pr_name, product.pr_price, product.pr_about, category.ct_name, inventory.stock_quantity, inventory.last_updated
                                FROM product
                                INNER JOIN category ON product.ct_id = category.ct_id
                                INNER JOIN inventory ON product.pr_id = inventory.pr_id
                                WHERE product.pr_id = :pr_id");
        $stmt->bindParam(':pr_id', $pr_id);
        $stmt->execute();

        $product_details = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($product_details);
    } catch(PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    echo "商品IDが提供されていません";
}
?>
