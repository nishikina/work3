<?php
include 'db_connect.php';

try {
    $db->beginTransaction();

    if (isset($_POST['od_id']) && isset($_POST['detail_id'])) {
        $orderId = $_POST['od_id'];
        $detailId = $_POST['detail_id'];

        // 注文明細のキャンセルフラグを更新するクエリ
        $query_cancel_detail = "UPDATE order_details SET cancelled_flag = 1 WHERE od_id = :od_id AND detail_id = :detail_id";
        $stmt_cancel_detail = $db->prepare($query_cancel_detail);
        $stmt_cancel_detail->bindParam(':od_id', $orderId);
        $stmt_cancel_detail->bindParam(':detail_id', $detailId);
        $stmt_cancel_detail->execute();

        // キャンセルされた注文明細の情報を取得
        $query_get_detail = "
            SELECT 
                od.pr_id, 
                od.quantity
            FROM 
                order_details od
            WHERE 
                od.od_id = :od_id
                AND od.detail_id = :detail_id
        ";
        $stmt_get_detail = $db->prepare($query_get_detail);
        $stmt_get_detail->bindParam(':od_id', $orderId);
        $stmt_get_detail->bindParam(':detail_id', $detailId);
        $stmt_get_detail->execute();
        $detailData = $stmt_get_detail->fetch(PDO::FETCH_ASSOC);

        if ($detailData) {
            $pr_id = $detailData['pr_id'];
            $quantity = $detailData['quantity'];

            // 在庫履歴テーブルに新しいレコードを挿入
            $stmt_insert_history = $db->prepare("INSERT INTO stock_history (pr_id, transaction_type, transaction_date, quantity, od_id) VALUES (?, 'cancel', CURRENT_TIMESTAMP(), ?, ?)");
            $stmt_insert_history->execute([$pr_id, $quantity, $orderId]);

            // 在庫テーブルの在庫数量を調整
            $stmt_update_inventory = $db->prepare("UPDATE inventory SET stock_quantity = stock_quantity + ? WHERE pr_id = ?");
            $stmt_update_inventory->execute([$quantity, $pr_id]);
        } else {
            throw new Exception('注文明細が見つかりませんでした。');
        }
    } else {
        throw new Exception('注文IDまたは明細IDが提供されていません。');
    }

    // トランザクションをコミット
    $db->commit();

    // キャンセルが成功した場合、更新された注文データを取得する
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
} catch (Exception $e) {
    // ロールバックしてエラーを処理
    $db->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
