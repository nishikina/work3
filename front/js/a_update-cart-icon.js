// XMLHttpRequestを使ってサーバーからカートの種類数を取得する関数
function getCartItemCount() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
              // カートの種類数を取得して更新
              var cartItemCount = parseInt(xhr.responseText);
              updateCartIcon(cartItemCount);
          } else {
              console.error('カートの種類数の取得に失敗しました');
          }
      }
  };
  xhr.open('GET', 'a_get_cart_item_count.php', true);
  xhr.send();
}

// カートのアイコンを更新する関数
function updateCartIcon(count) {
  var cartItemCountElement = document.getElementById('cartItemCount');
  cartItemCountElement.textContent = count;
}

// ページが読み込まれたときにカートの種類数を取得して更新
document.addEventListener('DOMContentLoaded', function() {
  getCartItemCount();
});
