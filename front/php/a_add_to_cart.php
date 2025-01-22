<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// フォームから送信された商品IDと数量を取得する
if (
    isset($_POST['product_id']) &&
    isset($_POST['product_name']) &&
    isset($_POST['quantity']) &&
    isset($_POST['product_price']) &&
    isset($_POST['stock_quantity'])
) {
    // 商品ID、商品名、数量、商品価格、在庫数を取得
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $quantity = intval($_POST['quantity']);
    $productPrice = floatval($_POST['product_price']);
    $stockQuantity = intval($_POST['stock_quantity']);

    // カート内の同じ商品の数量を合計する
    $totalQuantityInCart = 0;
    if (array_key_exists($productId, $_SESSION['cart'])) {
        // 既にカートに同じ商品が存在する場合、数量を合計する
        $totalQuantityInCart += $_SESSION['cart'][$productId]['quantity'];
    }

    // カートに追加する数量を合計した合計数量
    $totalQuantityToAdd = $totalQuantityInCart + $quantity;

    // 在庫数と合計数量を比較する
    if ($totalQuantityToAdd > $stockQuantity) {
        // 在庫数を超える場合はエラーメッセージを返す
        http_response_code(400); // ステータスコード400を返す
        echo '選択した数量が在庫数を超えています。在庫数: ' . $stockQuantity . '、合計購入予定数: ' . $totalQuantityToAdd;
        exit();
    } else {
        // 在庫数よりも少ない場合はカートに追加処理を行う
        if (array_key_exists($productId, $_SESSION['cart'])) {
            // 既にカートに同じ商品が存在する場合、数量と合計金額を更新する
            $_SESSION['cart'][$productId]['pr_name'] = $productName;
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
            $_SESSION['cart'][$productId]['total_price'] += $quantity * $productPrice; // 合計金額を更新
        } else {
            // カートに新しい商品を追加する
            $_SESSION['cart'][$productId] = array(
                'pr_id' => $productId, // 商品IDをセット
                'pr_name' => $productName, // 商品名をセット
                'quantity' => $quantity, // 数量をセット
                'product_price' => $productPrice, // 商品価格をセット
                'total_price' => $quantity * $productPrice // 合計金額を計算してセット
            );
        }

        // カート内の商品数を計算する
        $itemCount = count($_SESSION['cart']);
        echo $itemCount;
    }
} else {
    // リクエストが不正な場合やデータが不足している場合はエラーメッセージを返す
    http_response_code(400); // ステータスコード400を返す
    echo '不正なリクエストです。';
}
?>
