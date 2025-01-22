<?php
session_start();
?>
<?php
include 'db_connect.php';

// 検索条件を取得
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$user_name = isset($_GET['user_name']) ? $_GET['user_name'] : '';
$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';

// 注文一覧を取得するクエリ
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
    WHERE 1=1"; // この行を追加

// 検索条件が提供されている場合は、クエリを追加
if (!empty($order_id)) {
    $query .= " AND o.od_id LIKE :order_id";
}
if (!empty($from_date)) {
    $query .= " AND o.od_date >= :from_date";
}
if (!empty($to_date)) {
    $query .= " AND o.od_date <= :to_date";
}
if (!empty($user_id)) {
    $query .= " AND o.user_id LIKE :user_id";
}
if (!empty($user_name)) {
    $query .= " AND u.user_name LIKE :user_name";
}
if (!empty($product_name)) {
    $query .= " AND p.pr_name LIKE :product_name";
}

$query .= " ORDER BY o.od_id, od.detail_id"; // この行を追加

$stmt = $db->prepare($query);

// プレースホルダに値をバインド
if (!empty($order_id)) {
    $stmt->bindValue(':order_id', '%' . $order_id . '%');
}
if (!empty($from_date)) {
    $stmt->bindValue(':from_date', $from_date);
}
if (!empty($to_date)) {
    $stmt->bindValue(':to_date', $to_date);
}
if (!empty($user_id)) {
    $stmt->bindValue(':user_id', '%' . $user_id . '%');
}
if (!empty($user_name)) {
    $stmt->bindValue(':user_name', '%' . $user_name . '%');
}
if (!empty($product_name)) {
    $stmt->bindValue(':product_name', '%' . $product_name . '%');
}

$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// グループ化された注文情報を格納する配列
$groupedOrders = [];

// 注文IDでグループ化
foreach ($orders as $order) {
    $orderId = $order['od_id'];
    if (!array_key_exists($orderId, $groupedOrders)) {
        $groupedOrders[$orderId] = [
            'od_id' => $order['od_id'],
            'od_date' => $order['od_date'],
            'user_id' => $order['user_id'],
            'user_name' => $order['user_name'],
            'user_address' => $order['user_address'],
            'total_amount' => 0, // 0または空の値を設定する
            'total_quantity' => 0, // 0または空の値を設定する
            'details' => [],
        ];
    }
    // 注文内容を追加
    $groupedOrders[$orderId]['details'][] = [
        'detail_id' => $order['detail_id'],
        'pr_id' => $order['pr_id'],
        'quantity' => $order['quantity'],
        'cancelled_flag' => $order['cancelled_flag'],
        'pr_name' => $order['pr_name'],
        'pr_price' => $order['pr_price'],
        'pr_img' => $order['pr_img'],
    ];
    // 合計金額と数量を計算
    $groupedOrders[$orderId]['total_amount'] += $order['pr_price'] * $order['quantity'];
    $groupedOrders[$orderId]['total_quantity'] += $order['quantity'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/b_main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>注文一覧</title>
</head>
<body>
<div class="b_pr_top">
<div class="b_tittle"><h2>Be.nature 注文一覧</h2>
<a href="../../back_main/php/back_main.php">管理画面へ戻る</a></div>
<div class="pr_tittle">
<div class="user">
<?php
if(isset($_SESSION['username'])) {
    echo "<div>ログイン中：{$_SESSION['username']} さん</div>";
} else {
    echo "<div>ログインしていません</div>";
}
?></div></div>
<div class="container">
    <!-- 検索フォーム -->
<div class="sarchform">
<form method="GET" action="">
    <label for="order_id" class="sarchlabel">注文ID</label><br>
    <input type="text" id="order_id" name="order_id" value="<?php echo $order_id; ?>" placeholder="数字/部分一致OK"><br>
    <label for="from_date" class="sarchlabel">注文日時（開始〜</label><br>
    <input type="text" id="from_date" name="from_date" value="<?php echo $from_date; ?>" placeholder="yyyymmdd"><br>
    <label for="to_date" class="sarchlabel">注文日時　〜終了)</label><br>
    <input type="text" id="to_date" name="to_date" value="<?php echo $to_date; ?>" placeholder="yyyymmdd"><br>
    <label for="user_id" class="sarchlabel">会員ID:</label><br>
    <input type="text" id="user_id" name="user_id" value="<?php echo $user_id; ?>" placeholder="数字/部分一致OK"><br>
    <label for="user_name" class="sarchlabel">注文者名:</label><br>
    <input type="text" id="user_name" name="user_name" value="<?php echo $user_name; ?>" placeholder="漢字/部分一致OK"><br>
    <label for="product_name" class="sarchlabel">商品名:</label><br>
    <input type="text" id="product_name" name="product_name" value="<?php echo $product_name; ?>" placeholder="部分一致OK"><br>
    <input type="submit" value="検索" class="sarchbtn">
</form></div>

    <!-- 注文一覧の表示 -->
    <div class="pr_table">
    <table border="1">
        <tr>
            <th>注文ID</th>
            <th>注文日時</th>
            <th>会員ID</th>
            <th>注文者名</th>
            <th>発送先住所</th>
            <th>合計金額</th>
            <th>合計数量</th>
            <th>注文内容</th>
        </tr>
        <?php foreach ($groupedOrders as $order): ?>
            <tr>
                <td><?php echo $order['od_id']; ?></td>
                <td><?php echo $order['od_date']; ?></td>
                <td><?php echo $order['user_id']; ?></td>
                <td><?php echo $order['user_name']; ?></td>
                <td><?php echo $order['user_address']; ?></td>
                <td id="total_amount_<?php echo $order['od_id']; ?>"><?php echo $order['total_amount']; ?></td>
                <td id="total_quantity_<?php echo $order['od_id']; ?>"><?php echo $order['total_quantity']; ?></td>

                <!-- 注文内容を表示 -->
                <td>
                    <table border="1">
                        <tr>
                            <th>明細ID</th>
                            <th>商品名</th>
                            <th>商品金額</th>
                            <th>商品数量</th>
                            <th>キャンセル</th>
                        </tr>
                        <?php foreach ($order['details'] as $detail): ?>
                            <tr class="<?php echo ($detail['cancelled_flag'] == 1) ? 'cancelled_flag' : ''; ?>">
                            <td><?php echo $order['od_id'] . '-' . $detail['detail_id']; ?></td>
                                <td><?php echo $detail['pr_name']; ?></td>
                                <td><?php echo $detail['pr_price']; ?></td>
                                <td><?php echo $detail['quantity']; ?></td>
                                <td>
                                    <button class="cancel-btn" data-order-id="<?php echo $order['od_id']; ?>"data-detail-id="<?php echo $detail['detail_id']; ?>">キャンセル</button>
                                    <button class="uncancel-btn" data-order-id="<?php echo $order['od_id']; ?>"data-detail-id="<?php echo $detail['detail_id']; ?>">キャンセル取り消し</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div></div></div>
    <script>
     // キャンセルボタンがクリックされた時の処理     
    $(".cancel-btn").click(function () {
    var orderId = $(this).data("order-id");
    var detailId = $(this).data("detail-id");
    var button = $(this);
    var uncancelBtn = button.siblings(".uncancel-btn");// キャンセル取り消しボタンを取得
    if (confirm("本当にキャンセルしますか？")) {
        $.ajax({
            type: "POST",
            url: "cancel_order_detail.php",
            data: {od_id: orderId, detail_id: detailId},
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                     // キャンセルが成功した場合、注文明細をグレーアウトする
                    button.closest('tr').addClass("cancelled_flag");
                    // キャンセル取り消しボタンを表示
                    uncancelBtn.show(); 
                    // キャンセルボタンを非表示
                    button.hide(); 
                    // 合計金額と合計数量を更新
                    updateTotal(orderId, detailId); 
                } 
                else {
                    alert("エラーが発生しました。");
                }
            },
            error: function (xhr, status, error) {              
            }
        });
    }
});

// キャンセル取り消しボタンがクリックされた時の処理
$(".uncancel-btn").click(function () {
    var orderId = $(this).data("order-id");
    var detailId = $(this).data("detail-id");
    var button = $(this); // ボタンを変数に格納
    var cancelBtn = button.siblings(".cancel-btn"); // キャンセルボタンを取得
    if (confirm("本当にキャンセルを取り消しますか？")) {
        $.ajax({
            type: "POST",
            url: "uncancel_order_detail.php",
            data: {od_id: orderId, detail_id: detailId},
            success: function (response) {
                var data = JSON.parse(response); // レスポンスデータをパース
                if (data.success) {
                    // キャンセル取り消しが成功した場合、注文明細のグレーアウトを解除する
                    button.closest('tr').removeClass("cancelled_flag");
                    // キャンセルボタンを表示
                    cancelBtn.show();
                    // キャンセル取り消しボタンを非表示
                    button.hide();
                    // 合計金額と合計数量を更新
                    updateTotal(orderId, detailId); 
                }
                 else {
                    alert("エラーが発生しました。");
                }
            }
        });
    }
});

function updateTotal(orderId, detailId) {
    console.log("注文ID:", orderId); // orderIdが正しく渡されているか確認するためのログ
    console.log("明細ID:", detailId); // detailIdが正しく渡されているか確認するためのログ
    $.ajax({
        type: "GET",
        url: "calculate_total.php",
        data: {od_id: orderId, detail_id: detailId},
        success: function (response) {
            console.log("レスポンス:", response); // レスポンスが正しく受け取れているか確認するためのログ
            var data = JSON.parse(response);
            console.log("合計金額:", data.total_amount); // 合計金額が正しく解釈されているか確認するためのログ
            console.log("合計数量:", data.total_quantity); // 合計数量が正しく解釈されているか確認するためのログ
            $("#total_amount_" + orderId).text(data.total_amount);
            $("#total_quantity_" + orderId).text(data.total_quantity);
        }
    });
}

</script>
</body>
</html>