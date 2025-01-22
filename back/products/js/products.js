document.addEventListener("DOMContentLoaded", function() {
});


// 初回は即座に商品情報を取得し、その後3分ごとに更新
document.addEventListener('DOMContentLoaded', updateProducts);
setInterval(updateProducts, 180000); //自動更新

// 商品情報を更新する関数
function updateProducts() {
    const now = new Date();
    const formattedDate = `${now.getFullYear()}/${now.getMonth() + 1}/${now.getDate()} ${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;
    document.getElementById('lastUpdated').innerText = `最終更新日時(3分毎に自動更新): ${formattedDate}`;
}

// 商品詳細を取得してモーダルウィンドウ1に表示する関数
function showProductDetails(pr_id) {
    $.ajax({
        type: 'GET',
        url: '../php/get_product_details.php',
        data: { pr_id: pr_id },
        success: function(response) {
            var productDetails = JSON.parse(response);
            var html = '<table border="1">';
            html += '<tr><th>商品ID</th><th>商品名</th><th>価格</th><th>商品説明</th><th>商品画像</th><th>カテゴリ</th><th>在庫数</th><th>最終入出庫日時</th></tr>';
            html += '<tr>';
            html += '<td>' + productDetails.pr_id + '</td>';
            html += '<td>' + productDetails.pr_name + '</td>';
            html += '<td>' + productDetails.pr_price + '</td>';
            var about = productDetails.pr_about ? productDetails.pr_about : "商品説明がありません";
            html += '<td>' + about + '</td>';
            html += '<td><img src="' + productDetails.pr_img + '" alt="商品画像"></td>';
            html += '<td>' + productDetails.ct_name + '</td>';
             html += '<td><a href="#" onclick="showStockHistory(' + productDetails.pr_id + ')">' + productDetails.stock_quantity + '</a></td>';
            html += '<td>' + productDetails.last_updated + '</td>';
            html += '</tr>';
            html += '</table>';
            html += '<button id="closeBtn" onclick="closeModal()">閉じる</button>';
            
            $('#modal-content').html(html);
            $('#modal').css('display', 'block');
        },
        error: function(xhr, status, error) {
            console.error('商品詳細の取得に失敗しました:', error);
        }
    });
}

// 在庫数を更新する関数
function updateStock() {
    var pr_id = $('#modal-content td:first-child').text(); // 商品IDを取得
    $.ajax({
        type: 'GET',
        url: '../php/get_product_details.php',
        data: { pr_id: pr_id },
        success: function(response) {
            var productDetails = JSON.parse(response);
            $('#stock_quantity').text(productDetails.stock_quantity); // 在庫数を更新
            // 商品一覧ページの該当商品の在庫数も更新
            $(`#stock_quantity_${pr_id}`).text(productDetails.stock_quantity);
        },
        error: function(xhr, status, error) {
            console.error('在庫数の更新に失敗しました:', error);
        }
    });
}

// モーダルウィンドウ1を閉じる関数
function closeModal() {
    $('#modal').css('display', 'none');
}

//ここからモーダルウィンドウ2 

// 入出庫タイプを変換する関数
function convertTransactionType(type) {
    switch (type) {
        case "add":
            return "入庫";
        case "sell":
            return "出庫";
        default:
            return type; // その他の場合はそのまま返す
    }
}

// 入出庫履歴を表示する関数
function showStockHistory(pr_id) {
    $.ajax({
        type: 'GET',
        url: '../php/get_stock_history.php',
        data: { pr_id: pr_id },
        success: function(response) {
            var stockHistory = JSON.parse(response);
            // 入出庫履歴をモーダルウィンドウ2に表示
            var html = '<h2>入出庫履歴</h2>';
            html += '<table>';
            html += '<tr><th>日時</th><th>入出庫タイプ</th></tr>';
            stockHistory.forEach(function(entry) {
                // 入出庫タイプを変換
                var transactionType = convertTransactionType(entry.transaction_type);
                html += '<tr>';
                html += '<td>' + entry.transaction_date + '</td>';
                html += '<td>' + transactionType + '</td>';
                html += '<td>' + entry.quantity + '</td>';
                html += '</tr>';  
            });
            html += '<button id="closeBtn2" onclick="closeModal2()">閉じる</button>'
            html += '</table>';
            $('#modal-content2').html(html);
            $('#modal2').css('display', 'block');
        },
        error: function(xhr, status, error) {
            console.error('入出庫履歴の取得に失敗しました:', error);
        }
    });
}
// モーダルウィンドウ2を閉じる関数
function closeModal2() {
    $('#modal2').css('display', 'none');
}
