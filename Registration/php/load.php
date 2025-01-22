<?php
require 'connect.php'; // データベース接続用のファイル

// POSTデータの受け取り
$table = $_POST['tableName'];
$id = $_POST['id'];

// 各テーブルに対応したIDカラムと検索カラムを指定
$idColumnMap = [
    'admins' => 'id',
    'users' => 'user_id',
    'suppliers' => 'sup_id',
    'product' => 'pr_id',
    'category' => 'ct_id'
];

$searchColumnMap = [
    'admins' => 'username,email,password',
    'users' => 'user_name,user_address,email,password,tel',
    'suppliers' => 'sup_name,address,email,phone_number',
    'product' => 'pr_name,pr_about,pr_price,purchase_price,pr_img,sup_id,ct_id',
    'category' => 'ct_name,parent_id'
];

// テーブル名がマッピングに存在するか確認
if (array_key_exists($table, $idColumnMap) && array_key_exists($table, $searchColumnMap)) {
    $idColumn = $idColumnMap[$table];
    $searchColumns = $searchColumnMap[$table];
    
    // SQLクエリの準備と実行
    $query = $pdo->prepare("SELECT $searchColumns FROM $table WHERE $idColumn = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    
    // 結果を取得
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        // 結果をJSON形式で返す
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
} else {
    echo json_encode(['error' => 'Invalid table name']);
}
?>
