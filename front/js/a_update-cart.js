
  // update-cart.js

// カートの表示を更新する関数
function updateCart() {
    var cartSection = document.querySelector('.cart-items');
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // カートの中身を取得して表示する
                cartSection.innerHTML = xhr.responseText;
            } else {
                // エラーが発生した場合の処理
                console.error('カートの更新中にエラーが発生しました');
            }
        }
    };
    xhr.open('GET', 'a_cart.php', true);
    xhr.send();
}

// ページ読み込み時にカートの表示を更新する
updateCart();
