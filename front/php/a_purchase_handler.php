<?php
// connect.phpを読み込む
require_once 'connect.php';

// セッションを開始
session_start();

// POSTリクエストが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたデータを受け取る
    $name = $_POST["name"];
    $tel = $_POST["tel"];
    $zip = $_POST["zip"];
    $pref = $_POST["pref"];
    $addr1 = $_POST["addr1"];
    $addr2 = $_POST["addr2"];
    $email = $_POST["email1"];
    $paymentMethod = $_POST["payment_method"];

    // ユーザーアドレスを組み立てる
    $user_address = $zip . " " . $pref . " " . $addr1 . " " . $addr2;

    try {
        // トランザクションを開始
        $pdo->beginTransaction();

        // 1. ユーザー情報を挿入し、ユーザーIDを取得
        $sql = "INSERT INTO users (user_name, user_address, email, tel) VALUES (:name, :address, :email, :tel)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $user_address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tel', $tel);
        $stmt->execute();
        $user_id = $pdo->lastInsertId(); // ユーザーIDを取得

        // 2. 注文情報を挿入
        $sql = "INSERT INTO orders (user_id, od_date) VALUES (:user_id, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $order_id = $pdo->lastInsertId(); // 注文IDを取得

        // 3. カートの内容をループして注文詳細を挿入
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product) {
                $product_id = $product['pr_id'];
                $quantity = $product['quantity'];

                $sql = "INSERT INTO order_details (od_id, pr_id, quantity) VALUES (:od_id, :pr_id, :quantity)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':od_id', $order_id);
                $stmt->bindParam(':pr_id', $product_id);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
                
                // 在庫変動履歴を記録
                $sql = "INSERT INTO stock_history (pr_id, od_id, transaction_type, transaction_date, quantity) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(1, $product_id, PDO::PARAM_INT);
                $stmt->bindValue(2, $order_id, PDO::PARAM_INT);
                $stmt->bindValue(3, 'sell', PDO::PARAM_STR);
                $stmt->bindValue(4, date('Y-m-d H:i:s'), PDO::PARAM_STR); // 現在の日時を取得
                $stmt->bindValue(5, -$quantity, PDO::PARAM_INT); // 在庫が減るため、負の数量を指定
                $stmt->execute();
                
                // 商品の在庫数量を更新
                $sql = "UPDATE inventory SET stock_quantity = stock_quantity - ? WHERE pr_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(1, $quantity, PDO::PARAM_INT);
                $stmt->bindValue(2, $product_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        // トランザクションをコミット
        $pdo->commit();

        // カートを空にする
        unset($_SESSION['cart']);

        // リダイレクト処理
        header("Location: ./a_complete.php");
        exit();
    } catch (Exception $e) {
        // トランザクション中にエラーが発生した場合はロールバック
        $pdo->rollBack();
        echo "エラー: " . $e->getMessage();
    }
}
?>

