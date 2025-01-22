function confirmReset() {
    // 確認メッセージを表示し、ユーザーが「OK」をクリックしたらリセットする
    var result = confirm("カートをリセットしてもよろしいですか？");
    if (result) {
      // OKをクリックした場合はリセット処理を実行
      window.location.href = 'a_reset_cart.php';
    } else {
      // キャンセルをクリックした場合は何もしない
    }
  }