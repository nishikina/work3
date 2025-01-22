<?php
include 'db_connect.php';

if(isset($_GET['pr_id'])) {
    $pr_id = $_GET['pr_id'];
    try {
        $stmt = $db->prepare("SELECT * FROM stock_history WHERE pr_id = :pr_id");
        $stmt->bindParam(':pr_id', $pr_id);
        $stmt->execute();

        $stock_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($stock_history);
    } catch(PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    echo "商品IDが提供されていません";
}
?>
