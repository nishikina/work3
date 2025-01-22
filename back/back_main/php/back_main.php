<?php
session_start();
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../css/b_main.css">

<title>管理画面</title>
</head>
<body>
    <div class="b_top">
<div class="b_tittle">
<h2>Be.nature 管理画面</h2>
<div class="b_user">
    <!--<img src="../../img/b_icon.png"></div>-->
<?php
if(isset($_SESSION['username'])) {
    echo "<p>ログイン中：{$_SESSION['username']} さん</p>";
} else {
    echo "<p>ログインしていません</p>";
}
?></div></div>
<hr>
<div class="bmenu">
<div class="bmenu_detail"><img src="../../img/logo.jpg"><br><a href="../../../../Shop_TeamB/front/php/a_index.php">Webサイト</a></div>
  <div class="bmenu_detail"><img src="../../img/b_product.png"><br><a href="../../products/php/products.php">商品管理</a></div>
  <div class="bmenu_detail"><img src="../../img/b_cart.png"><br><a href="../../orders/php/order_management.php">受注管理</a></div>
  <div class="bmenu_detail"><img src="../../img/b_muster.png"><br><br><a href="../../../../Shop_TeamB/Registration/php/portal.php">マスター管理</a></div>
</div>

<h2>売上管理</h2>
<hr>
<div class="bmenu">
    <div id="sales_report" class="analysis_table-style">
        <!-- ここに売上速報が表示されます --> 
    </div>
    <div id="ranking_report" class="analysis_table-style">
        <!-- ここに売上ランキングが表示されます -->
        </table>
    </div>
    <div id="stock_alert_report" class="analysis_table-style">
        <!-- ここに在庫数アラートが表示されます -->
    </div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>  
function fetchData() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            document.getElementById("sales_report").innerHTML = data.sales_report;
            document.getElementById("ranking_report").innerHTML = data.ranking_report;
            document.getElementById("stock_alert_report").innerHTML = data.stock_alert_report;
        }
    };
    xhttp.open("GET", "fetch_data.php", true);
    xhttp.send();
}
// 10 分ごとに自動更新
setInterval(fetchData, 10 * 60 * 1000);
// 最初の読み込み
fetchData();
</script>

</body>
</html>
