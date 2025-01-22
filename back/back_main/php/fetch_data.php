<?php
include 'db_connect.php';

try {
    // 売上速報のクエリ
    $sales_report_query = "SELECT COUNT(*) AS sales_count, SUM(od.quantity * p.pr_price) AS total_amount
                          FROM order_details od
                          JOIN product p ON od.pr_id = p.pr_id
                          WHERE DATE(od.od_id) = CURDATE()";
    // クエリの実行と結果の取得
    $sales_result = $db->query($sales_report_query);
    $sales_row = $sales_result->fetch(PDO::FETCH_ASSOC);

    // 売上速報の表示
    $sales_report = "<div id='sales_report' class='analysis'>";
    $sales_report .= "<h3>売上速報</h3>";
    $sales_report .= "<table border='1'>";
    $sales_report .= "<tr><th>売上件数</th><th>総売上金額</th></tr>";
    $sales_report .= "<tr><td>" . $sales_row['sales_count'] . "</td><td>" . $sales_row['total_amount'] . "円</td></tr>";
    $sales_report .= "</table>";
    $sales_report .= "</div>";

    
// ランキングのクエリ
$ranking_query = "SELECT p.pr_name, SUM(od.quantity) AS total_sales,
@rank := IF(@prev_sales = SUM(od.quantity), @rank, @rank + 1) AS rank,
@prev_sales := SUM(od.quantity) AS prev_sales
FROM order_details od
JOIN product p ON od.pr_id = p.pr_id
CROSS JOIN (SELECT @rank := 0, @prev_sales := NULL) r
GROUP BY p.pr_name
ORDER BY total_sales DESC
LIMIT 5";
// クエリの実行と結果の取得
$ranking_result = $db->query($ranking_query);

// ランキングの表示
$ranking_report = "<div id='ranking_report' class='analysis_table-style'>";
$ranking_report .= "<h3>売上ランキング</h3>";
$ranking_report .= "<table border='1'>";
$ranking_report .= "<tr><th>順位</th><th>商品名</th><th>売上個数</th></tr>";
if ($ranking_result->rowCount() > 0) {
    $rank = 1;
    while ($row = $ranking_result->fetch(PDO::FETCH_ASSOC)) {
        $ranking_report .= "<tr><td>" . $rank . "</td><td>" . $row['pr_name'] . "</td><td>" . $row['total_sales'] . "個</td></tr>";
        $rank++; // 次の商品の順位を更新
    }
} else {
$ranking_report .= "<tr><td colspan='3'>ランキングはありません。</td></tr>";
}
$ranking_report .= "</table>";
$ranking_report .= "</div>";

    // 在庫数が少ない順に商品名を取得するクエリ
    $stock_query = "SELECT p.pr_name, i.stock_quantity
                    FROM product p
                    JOIN inventory i ON p.pr_id = i.pr_id
                    WHERE i.stock_quantity <= 7
                    ORDER BY i.stock_quantity ASC";
    // クエリの実行と結果の取得
    $stock_result = $db->query($stock_query);

    // 在庫数の表示
    $stock_alert_report = "<div id='stock_report' class='analysis_table-style'>";
    $stock_alert_report .= "<h3>要発注商品（在庫数7以下）</h3>";
    $stock_alert_report .= "<table border='1'>";
    $stock_alert_report .= "<tr><th>商品名</th><th>在庫数</th></tr>";
    if ($stock_result->rowCount() > 0) {
        while ($row = $stock_result->fetch(PDO::FETCH_ASSOC)) {
            $stock_alert_report .= "<tr><td>" . $row['pr_name'] . "</td><td>" . $row['stock_quantity'] . "</td></tr>";
        }
    } else {
        $stock_alert_report .= "<tr><td colspan='2'>該当する商品はありません。</td></tr>";
    }
    $stock_alert_report .= "</table>";
    $stock_alert_report .= "</div>";

    // JSON 形式でデータを返す
    $data = array(
        'sales_report' => $sales_report,
        'ranking_report' => $ranking_report,
        'stock_alert_report' => $stock_alert_report
    );
    echo json_encode($data);
} catch(PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}

?>