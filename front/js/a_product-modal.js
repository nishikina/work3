// 商品詳細を取得してモーダルを表示する関数
function showProductModal(productId) {
    // モーダルを表示するための処理
    const modal = document.querySelector('#detailModal');
    const modalTitle = modal.querySelector('#modalTitle');
    const productDescription = modal.querySelector('#productDescription');
    const productPrice = modal.querySelector('#productPrice');
    const productImages = modal.querySelector('.product-images');

    // 商品詳細を取得するAjaxリクエスト
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'a_get_product_detail.php?product_id=' + productId, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const productDetail = JSON.parse(xhr.responseText);
            modalTitle.textContent = productDetail.pr_name;
            productDescription.textContent = productDetail.pr_about;
            productPrice.textContent = '価格(税込): ¥' + productDetail.pr_price;

            // Slickスライダーの解除
            if ($(productImages).hasClass('slick-initialized')) {
                $(productImages).slick('unslick'); // 既に初期化されている場合は解除
            }

            // 以前の画像をクリア
            productImages.innerHTML = '';

            // メインイメージを表示
            const mainImage = document.createElement('img');
            mainImage.src = productDetail.pr_img;
            productImages.appendChild(mainImage);

            // 追加画像を表示
            if (productDetail.img02) {
                const img02 = document.createElement('img');
                img02.src = productDetail.img02;
                productImages.appendChild(img02);
            }
            if (productDetail.img03) {
                const img03 = document.createElement('img');
                img03.src = productDetail.img03;
                productImages.appendChild(img03);
            }
            if (productDetail.img04) {
                const img04 = document.createElement('img');
                img04.src = productDetail.img04;
                productImages.appendChild(img04);
            }

            // Slickスライダーの初期化
            $(productImages).slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true
            });

            // モーダルを表示する
            modal.style.display = 'block';
        }
    };

    xhr.send();
}

document.addEventListener('DOMContentLoaded', function() {
    const products = document.querySelectorAll('.product');

    products.forEach(product => {
        const productId = product.getAttribute('data-product-id');
        const productImg = product.querySelector('.product-img');

        productImg.addEventListener('click', function() {
            showProductModal(productId);
        });
    });

    // モーダルの閉じるボタンをクリックしたときの処理
    const closeButtons = document.querySelectorAll('.close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = button.closest('.product_modal');
            modal.style.display = 'none';
        });
    });

    // モーダルの外側をクリックしたときに閉じる処理
    window.addEventListener('click', function(event) {
        const modal = document.querySelector('#detailModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});
