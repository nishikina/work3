document.addEventListener('DOMContentLoaded', function() {
    // 商品詳細モーダルの表示を設定する関数
    function setupProductModal() {
        const products = document.querySelectorAll('.product');

        products.forEach(product => {
            const productId = product.getAttribute('data-product-id');
            const productImg = product.querySelector('.product-img');

            productImg.addEventListener('click', function() {
                // 商品詳細を取得してモーダルを表示
                showProductModal(productId);
            });
        });
    }

    // ボタンのクリックイベントを処理する関数
    function handleAddToCartButtonClick(button) {
        return function() {
            var productId = button.getAttribute('data-pr-id');
            var productName = button.getAttribute('data-pr-name');
            var productPrice = button.getAttribute('data-pr-price');
            var quantityInput = button.parentNode.querySelector('.quantity-input');
            var quantity = quantityInput.value;
            var stockQuantity = parseInt(button.getAttribute('data-stock-quantity'));

            // インプットされた数量が在庫数を超えていないかチェック
            if (parseInt(quantity) > stockQuantity) {
                alert('選択した数量が在庫数を超えています。');
                return; // 処理を中断してカートに商品を追加しない
            }

            // サーバーに商品ID、商品名、金額、数量、在庫数を送信する
            addToCart(productId, productName, productPrice, quantity, stockQuantity);

            // 入力フィールドをリセット
            quantityInput.value = 0;
        };
    }

    // フォームが送信されたときの処理
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // デフォルトのフォーム送信をキャンセル

        // フォームデータを取得
        var formData = new FormData(this);

        // Ajaxリクエストを送信
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'a_filter_products.php?' + new URLSearchParams(formData).toString(), true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                // レスポンスを受け取った後の処理
                document.getElementById('products').innerHTML = xhr.responseText;
                
                // カートに追加ボタンにイベントリスナーを再設定
                var addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
                addToCartButtons.forEach(function(button) {
                    button.addEventListener('click', handleAddToCartButtonClick(button));
                });

                // 商品詳細モーダルの表示を再設定
                setupProductModal();
            }
        };
        xhr.send();
    });

    // 初期表示時に商品詳細モーダルの表示を設定
    setupProductModal();
});
