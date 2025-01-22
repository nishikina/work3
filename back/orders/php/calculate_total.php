<?php
include 'db_connect.php';

if (isset($_GET['od_id'])) {
    $orderId = $_GET['od_id'];
    // 注文に関連する明細の合計金額と合計数量を計算するクエリ
    $query = "SELECT 
    SUM(p.pr_price * od.quantity) AS total_amount, 
    SUM(od.quantity) AS total_quantity
    FROM 
    order_details od
    INNER JOIN 
    product p ON od.pr_id = p.pr_id
    WHERE 
    od.od_id = :od_id AND od.cancelled_flag != 1;";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':od_id', $orderId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // JSON形式で合計金額と合計数量を返す
    echo json_encode($result);
} else {
    // エラー時の処理（注文IDが指定されていない場合）
    echo json_encode(['error' => '注文IDが指定されていません']);
}
?>
