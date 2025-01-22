<div class="title">User管理</div>

<div id="table" data-value="users" style="display: none;"></div>
<label for="Id">UserID:</label>
<input type="text" id="Id" name="Id" required>
<button id="loadBtn">編集</button><br>

<form action="add_user.php" method="POST">
    <label for="user_name">ユーザー名:</label>
    <input type="text" id="user_name" name="user_name" required><br>
    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email" required><br>
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required><br>
    <label for="tel">電話番号:</label>
    <input type="tel" id="tel" name="tel"><br>
    <input type="submit" value="登録">
</form>


