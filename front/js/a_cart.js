// 商品の個数を減らす関数
function decrementQuantity(key, quantity) {
    if (quantity > 1) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    updateCart();
                } else {
                    console.error('カートの更新中にエラーが発生しました');
                }
            }
        };
        xhr.open('POST', 'a_update_quantity.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('key=' + key + '&quantity=' + (quantity - 1)); // Decrease quantity by 1
    } else {
        console.log('Quantity cannot be less than 1');
    }
}
// 商品の個数を増やす関数
function incrementQuantity(key, quantity) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                updateCart();
            } else {
                console.error('カートの更新中にエラーが発生しました');
            }
        }
    };
    xhr.open('POST', 'a_update_quantity.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('key=' + key + '&quantity=' + (quantity + 1)); // Increase quantity by 1
}


// カートから商品を削除する関数
function removeFromCart(key) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                updateCart();
            } else {
                console.error('カートの更新中にエラーが発生しました');
            }
        }
    };
    xhr.open('POST', 'a_remove_from_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('key=' + key); // Send the key of the item to be removed
}
