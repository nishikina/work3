<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Be.nature</title>
    <link rel="stylesheet" href="../css/portal.css">
</head>
<body>

<header>
    <img src="../item/logo.jpg" alt="Be.nature ロゴ" class="logo">
</header>

<div class="container">
    <div class="sidebar">
        <button class="accordion" data-table="admins">マスタ管理</button>
        <div class="accordion-content">
            <button class="sub-button" data-action="edit_admin.php">Admin</button>
            <button class="sub-button" data-action="edit_user.php">User</button>
            <button class="sub-button" data-action="edit_suppl.php">supplier</button>
            <button class="sub-button" data-action="edit_product.php">product</button>
            <button class="sub-button" data-action="edit_cate.php">category</button>
        </div>
    </div>
    <div class="apper">
        <div id="data-container"></div>
    </div>
</div>

<footer>
    <p>&copy; 2024 Bteam</p>
</footer>

<script src="../js/portal.js"></script>

</body>
</html>
