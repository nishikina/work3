// showError 関数の定義
function showError(element, message) {
    var errorSpan = element.parentNode.querySelector('.error-message') || document.createElement('span');
    errorSpan.textContent = message;
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
    if (inputId !== "tel" || value) {
        if (!value || (pattern && !pattern.test(value))) {
            showError(input, errorMessage);
            input.style.backgroundColor = "pink";
        } else {
            showError(input, "");
            input.style.backgroundColor = "#d9ead3";
        }
    } else {
        showError(input, "");
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
            input.addEventListener("input", function () {
                validateInput(inputId, pattern, errorMessage);
            });
        } else {
            console.error("Input element not found with ID:", inputId);
        }
    });
}

// フィールドごとのバリデーションを追加
addValidationListener("name", null, "名前は必須です");
addValidationListener("tel", /^[0-9]{3}-[0-9]{4}-[0-9]{4}$/, "適切な桁数及び半角数字でご入力ください");
addValidationListener("email1", /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, "メールアドレスには@やドメインが必要です");
addValidationListener("inquiry_type", null, "お問い合わせの種類を選択してください");
addValidationListener("subject", null, "件名は必須です");
addValidationListener("content", null, "お問い合わせ内容は必須です");

// フォーム送信時のイベントリスナーを追加
document.getElementById('myForm').addEventListener('submit', function(event) {
    validateInput("name", null, "名前は必須です");
    validateInput("tel", /^[0-9]{3}-[0-9]{4}-[0-9]{4}$/, "適切な桁数及び半角数字でご入力ください");
    validateInput("email1", /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, "メールアドレスには@やドメインが必要です");
    validateInput("inquiry_type", null, "お問い合わせの種類を選択してください");
    validateInput("subject", null, "件名は必須です");
    validateInput("content", null, "お問い合わせ内容は必須です");
    var errorMessages = document.querySelectorAll('.error-message');
    var hasErrors = Array.from(errorMessages).some(function(element) {
        return element.textContent.trim() !== '';
    });
    if (hasErrors) {
        event.preventDefault();
        alert('エラーがあります。フォームを正しく入力してください。');
    }
});
