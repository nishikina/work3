<div class="title">Category管理</div>

<div id="table" data-value="category" style="display: none;"></div>
<label for="Id">CategoryID:</label>
<input type="text" id="Id" name="Id" required>
<button id="loadBtn">編集</button><br>

<form action="add_category.php" method="POST">
    <label for="ct_name">カテゴリ名:</label>
    <input type="text" id="ct_name" name="ct_name" required><br>
    <label for="parent_id">親カテゴリ:</label>
    <input type="text" id="parent_id" name="parent_id" required><br>
    <input type="submit" value="登録">
</form>