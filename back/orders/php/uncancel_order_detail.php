<?php
include 'db_connect.php';

if (isset($_POST['od_id']) && isset($_POST['detail_id'])) {
    $orderId = $_POST['od_id'];
    $detailId = $_POST['detail_id'];

    try {
        // トランザクションを開始
        $db->beginTransaction();

        // 注文明細のキャンセルフラグを取り消すクエリ
        $query = "UPDATE order_details SET cancelled_flag = 0 WHERE od_id = :od_id AND detail_id = :detail_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':od_id', $orderId);
        $stmt->bindParam(':detail_id', $detailId);
        $stmt->execute();

        // 在庫履歴に新しいレコードを挿入するクエリ
        $query = "INSERT INTO stock_history (pr_id, transaction_type, transaction_date, quantity, od_id) SELECT pr_id, 'cancel_revert', CURRENT_TIMESTAMP(), -quantity, od_id FROM order_details WHERE od_id = :od_id AND detail_id = :detail_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':od_id', $orderId);
        $stmt->bindParam(':detail_id', $detailId);
        $stmt->execute();

        // 在庫テーブルの在庫数量を調整するクエリ
        $query = "
            UPDATE inventory AS i
            INNER JOIN (
                SELECT pr_id, SUM(quantity) AS total_cancelled FROM order_details WHERE od_id = :od_id AND detail_id = :detail_id
            ) AS od ON i.pr_id = od.pr_id
            SET i.stock_quantity = i.stock_quantity - od.total_cancelled
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':od_id', $orderId);
        $stmt->bindParam(':detail_id', $detailId);
        $stmt->execute();

        // コミット
        $db->commit();

        // キャンセル取り消しが成功した場合、更新された注文データを取得する
        $query = "
            SELECT 
                o.od_id, 
                o.od_date, 
                o.user_id, 
                u.user_name, 
                u.user_address, 
                od.detail_id, 
                od.pr_id, 
                od.quantity, 
                od.cancelled_flag, 
                p.pr_name, 
                p.pr_price, 
                p.pr_img
            FROM 
                orders o
                INNER JOIN users u ON o.user_id = u.user_id
                INNER JOIN order_details od ON o.od_id = od.od_id
                INNER JOIN product p ON od.pr_id = p.pr_id
            WHERE 
                o.od_id = :od_id
            ORDER BY
                od.detail_id
        ";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':od_id', $orderId);
        $stmt->execute();
        $orderData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'orderData' => $orderData]); // 更新された注文データを返す
    } catch (PDOException $e) {
        // ロールバック
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
}
?>
