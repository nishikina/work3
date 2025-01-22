<div class="title">Supplier管理</div>

<div id="table" data-value="suppliers" style="display: none;"></div>
<label for="Id">SupplierID:</label>
<input type="text" id="Id" name="Id" required>
<button id="loadBtn">編集</button><br>

<form action="add_supplier.php" method="POST">
    <label for="sup_name">サプライヤー名:</label>
    <input type="text" id="sup_name" name="sup_name" required><br>
    <label for="address">住所:</label>
    <input type="text" id="address" name="address"><br>
    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email" required><br>
    <label for="contact_person">連絡担当者:</label>
    <input type="text" id="contact_person" name="contact_person"><br>
    <label for="phone_number">電話番号:</label>
    <input type="tel" id="phone_number" name="phone_number"><br>
    <input type="submit" value="登録">
</form>