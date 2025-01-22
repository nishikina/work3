<?php
// サーバー側での処理が必要なため、適宜修正してください。

// データベースに接続する処理
require_once 'connect.php';

// 商品IDがリクエストパラメータで送られてきたかを確認
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    try {
        // 商品情報をデータベースから取得
        $stmt = $pdo->prepare("
            SELECT p.*, pi.img02, pi.img03, pi.img04
            FROM product p
            LEFT JOIN product_imgs pi ON p.pr_id = pi.pr_id
            WHERE p.pr_id = :product_id
        ");
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        
        // 取得した商品情報を連想配列で格納
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // 商品情報をJSON形式で出力
        header('Content-Type: application/json');
        echo json_encode($product);
    } catch (PDOException $e) {
        // エラーが発生した場合の処理
        echo json_encode(array('error' => 'データベースエラー: ' . $e->getMessage()));
    }
} else {
    // 商品IDが送られてこなかった場合の処理
    echo json_encode(array('error' => '商品IDが指定されていません'));
}
?>
