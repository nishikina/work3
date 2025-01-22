<div class="title">Product管理</div>

<div id="table" data-value="product" style="display: none;"></div>
<label for="Id">ProductID:</label>
<input type="text" id="Id" name="Id" required>
<button id="loadBtn">編集</button><br>

<form action="add_product.php" method="POST">
    <label for="pr_name">商品名:</label>
    <input type="text" id="pr_name" name="pr_name" required><br>
    <label for="pr_about">商品説明:</label>
    <textarea id="pr_about" name="pr_about" required></textarea><br>
    <label for="pr_price">販売価格:</label>
    <input type="number" id="pr_price" name="pr_price" required><br>
    <label for="purchase_price">仕入価格:</label>
    <input type="number" id="purchase_price" name="purchase_price" required><br>
    <label for="pr_img">商品画像:</label>
    <input type="file" id="pr_img" name="pr_img" accept="image/*" required><br>
    <label for="sup_id">仕入先ID:</label>
    <input type="number" id="sup_id" name="sup_id" required><br>
    <label for="ct_id">カテゴリID:</label>
    <input type="number" id="ct_id" name="ct_id" required><br>
    <input type="submit" value="登録">
</form>
