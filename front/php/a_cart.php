<?php
session_start();

// カートが空でない場合のみ中身を表示する
if (!empty($_SESSION['cart'])) {
    $totalCartPrice = 0; // カートの合計金額を初期化
}
?>
<link rel="stylesheet" href="../css/a_cart.css"> 

<?php
if (!empty($_SESSION['cart'])) {
    ?>
    <table class="cart-table">
        <tr>
            <th>商品名</th>
            <th>単価(税込)</th>
            <th>購入数量</th>
            <th>合計金額(税込)</th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $product) : ?>
            <tr class="cart-item">
                <td><?= $product['pr_name'] ?></td>
                <td>¥<?= $product['product_price'] ?></td>
                <td><?= $product['quantity'] ?></td>
                <td>¥<?= $product['total_price'] ?></td>
            </tr>
            <?php
            // 合計金額に商品ごとの合計金額を加算
            $totalCartPrice += $product['total_price'];
        endforeach; ?>
    </table>

    <p class="total-price">カートの合計金額(税込): ¥<?= $totalCartPrice ?></p>

    <div style="text-align: center;">
    <form action="a_order.php" method="post" style="display: inline-block;">
        <button type="submit" style="margin: 50px 50px 0 10px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">レジに進む</button>
    </form>
</div>
<?php
} else {
    echo '<p>カートに商品がありません。</p>';
}
?>

<div style="text-align: center;">
    <button onclick="confirmReset()" style="padding: 10px 20px; margin: 10px;background-color: #f44336; color: white; border: none; border-radius: 4px;">カートをリセット</button>
</div>