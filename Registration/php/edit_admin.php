<div class="title">Admin管理</div>

<div id="table" data-value="admins" style="display: none;"></div>
<label for="Id">ID:</label>
<input type="text" id="Id" name="Id" required>
<button id="loadBtn">編集</button><br>

<form action="add_admin.php" method="POST">
    <label for="username">ユーザー名:</label>
    <input type="text" id="username" name="user_name" required><br>
    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email" required><br>
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required><br>
    <input type="submit" value="登録">
</form>
