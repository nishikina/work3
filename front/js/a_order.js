
// 入力フィールド用のイベントリスナー
addValidationListener("pref", null, "都道府県は必須です");
addValidationListener("addr1", null, "市区町村は必須です");
addValidationListener("addr2", null, "町域は必須です");

// バリデーション関数
function validateAddressFields() {
    validateInput("pref", null, "都道府県は必須です");
    validateInput("addr1", null, "市区町村は必須です");
    validateInput("addr2", null, "町域は必須です");
}

$(document).ready(function(){
    $('.ajaxzip3').on('click', function(){
        AjaxZip3.zip2addr('zip', '', 'pref', 'addr1', 'addr2');
    });

    // 成功時に実行する処理
    AjaxZip3.onSuccess = function(){
        $('.addr2').focus();
        validateAddressFields(); // 都道府県、市区町村、町域のバリデーションを実行
};
    

    // 失敗時に実行する処理
    AjaxZip3.onFailure = function(){
        alert('郵便番号に該当する住所が見つかりません');
        validateAddressFields(); // 都道府県、市区町村、町域のバリデーションを実行
    };
});

// showError 関数の定義
function showError(element, message) {
    // エラーメッセージを表示する span 要素を取得
    var errorSpan = element.parentNode.querySelector('.error-message') || document.createElement('span');

    // エラーメッセージの内容を設定
    errorSpan.textContent = message;

    // エラーメッセージが存在しない場合は新しく作成
    if (!element.parentNode.contains(errorSpan)) {
        errorSpan.classList.add('error-message');
        element.parentNode.appendChild(errorSpan);
    }
}

// 入力フィールドのバリデーション関数
function validateInput(inputId, pattern, errorMessage) {
    var input = document.getElementById(inputId);

    if (!input) {
        console.error("Input element not found with ID:", inputId);
        return;
    }

    var value = input.value;

    // Check if pattern is defined before using it
    if (!value || (pattern && !pattern.test(value))) {
        showError(input, errorMessage);
        input.style.backgroundColor = "pink";
    } else {
        showError(input, "");  // エラーメッセージを空にすることで、既存のエラーメッセージを削除
        input.style.backgroundColor = "#d9ead3";
    }
}


// 入力フィールド用のイベントリスナー
function addValidationListener(inputId, pattern, errorMessage) {
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById(inputId);

        if (input) {
            input.addEventListener("blur", function () {
                validateInput(inputId, pattern, errorMessage);
            });

            // イベントリスナーを変更：入力値が変更された時にも即座にエラーチェックを行う
            input.addEventListener("input", function () {
                validateInput(inputId, pattern, errorMessage);
            });
        } else {
            console.error("Input element not found with ID:", inputId);
        }
    });
}


// 異なるフィールドに対するバリデーション
addValidationListener("name", null, "名前は必須です");
addValidationListener("tel", /^[0-9]{3}-[0-9]{4}-[0-9]{4}$/, "適切な桁数及び半角数字でご入力ください");
addValidationListener("zip", /^[0-9]{3}-[0-9]{4}$/, "適切な桁数及び半角数字でご入力ください");
addValidationListener("pref", null, "都道府県は必須です");
addValidationListener("addr1", null, "市区町村は必須です");
addValidationListener("addr2", null, "町域・番地は必須です");
addValidationListener("email1", /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, "メールアドレスには@やドメインが必要です");

// ラジオボタンにエラーメッセージをクリアするためのイベントリスナーを追加
var radioButtons = document.querySelectorAll('input[name="radio"]');
radioButtons.forEach(function (radioButton) {
    radioButton.addEventListener('change', function () {
        document.getElementById("radioErrorMessage").textContent = "";
    });
});

// フォーム送信時のイベントリスナーを追加
$('form.validationForm').on('submit', function(event) {
    // すべてのフィールドに対してバリデーションを実行
    validateInput("name", null, "名前は必須です");
    validateInput("tel", /^[0-9]{3}-[0-9]{4}-[0-9]{4}$/, "適切な桁数及び半角数字でご入力ください");
    validateInput("zip", /^[0-9]{3}-[0-9]{4}$/, "適切な桁数及び半角数字でご入力ください");
    validateInput("pref", null, "都道府県は必須です");
    validateInput("addr1", null, "市区町村は必須です");
    validateInput("addr2", null, "町域・番地は必須です");
    validateInput("email1", /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, "メールアドレスには@やドメインが必要です");

    // ラジオボタンの選択を確認
    let selectedValue = document.querySelector('input[name="payment_method"]:checked');
    if (!selectedValue) {
        document.getElementById("radioErrorMessage").textContent = "お支払い方法を選択してください。";
        event.preventDefault(); // エラーがある場合、フォームの送信を防ぐ
    } else {
        document.getElementById("radioErrorMessage").textContent = "";

        // 送信確認のアラート
        if(confirm('送信しますか？')) {
            return true; // フォームを送信
        } else {
            event.preventDefault(); // キャンセルされた場合、フォームの送信を防ぐ
        }
    }

    // エラーメッセージが存在するか確認
    var errorMessages = document.querySelectorAll('.error-message');
    var hasErrors = Array.from(errorMessages).some(function(element) {
        return element.textContent.trim() !== '';
    });

    // エラーがある場合、フォームの送信を防ぐ
    if (hasErrors) {
        event.preventDefault();
        alert('エラーがあります。フォームを正しく入力してください。');
    }
});



// パスワード表示/非表示切り替えの関数
function togglePasswordVisibility() {
    var passwordInput = $('#password');
    var passwordFieldType = passwordInput.attr('type');
    passwordInput.attr('type', passwordFieldType === 'password' ? 'text' : 'password');
}
