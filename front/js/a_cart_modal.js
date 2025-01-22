document.addEventListener('DOMContentLoaded', function() {
    // カートを開くボタンを取得
    var cartOpenButton = document.querySelector('.header__navi a[href="a_cart.php"]');

    // モーダルを開くボタンがクリックされたときの処理を追加
    cartOpenButton.addEventListener('click', function(event) {
        event.preventDefault(); // デフォルトの動作をキャンセル

        // モーダルを表示するための関数を呼び出す
        openModal();
    });

    // モーダルの×印をクリックしたときにモーダルを閉じる処理
    var closeButton = document.querySelector('.modal-content .close');
    closeButton.addEventListener('click', function() {
        closeModal();
    });
});

// モーダルを開くための関数
function openModal() {
    var modal = document.getElementById('cartModal');
    modal.style.display = 'block';
}

// モーダルを閉じるための関数
function closeModal() {
    var modal = document.getElementById('cartModal');
    modal.style.display = 'none';
}

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
    xhr.open('GET', 'a_cart.php', true); // your_php_file.php を実際のファイル名に変更する
    xhr.send();
}
