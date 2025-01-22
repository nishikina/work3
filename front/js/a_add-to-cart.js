// カートアイテム数を更新する関数
function updateCartItemCount(count) {
    var cartItemCountElement = document.getElementById('cartItemCount');
    cartItemCountElement.textContent = count;
}

// カートに商品を追加する関数
function addToCart(productId, productName, productPrice, quantity, stockQuantity) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // 成功した場合、カートに追加されたことをユーザーに通知するなどの処理を行う
                alert('カートに商品が追加されました！');
                // カートの表示を更新
                updateCart();
                // カートアイテム数を更新
                updateCartItemCount(xhr.responseText);
                // 入力フィールドをリセット
                document.querySelector('.quantity-input').value = 0;
            } else if (xhr.status === 400) {
                // エラーが発生した場合の処理
                alert(xhr.responseText); // エラーメッセージを表示
            }
        }
    };
    xhr.open('POST', 'a_add_to_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // 商品ID、商品名、金額、数量、在庫数を送信する
    xhr.send('product_id=' + productId + '&product_name=' + encodeURIComponent(productName) + '&product_price=' + productPrice + '&quantity=' + quantity + '&stock_quantity=' + stockQuantity);
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
        
        // インプットされた数量が0の場合はアラートで警告
        if (parseInt(quantity) === 0) {
                alert('個数を入力してください。');
                    return; // 処理を中断してカートに商品を追加しない
        }

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

document.addEventListener('DOMContentLoaded', function() {
    var addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

    addToCartButtons.forEach(function(button) {
        button.addEventListener('click', handleAddToCartButtonClick(button));
    });
});
