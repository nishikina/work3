<?php
// データベース接続設定ファイルを読み込む
require_once 'connect.php';

// テーブル名を受け取る
$table = $_GET['table'];

try {
    // テーブル名に対応するSQLクエリを作成
    $sql = "SHOW COLUMNS FROM $table";
    $stmt = $pdo->query($sql);

    // カラム名の配列を初期化
    $columns = array();

    // カラムが存在する場合は配列に格納する
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columns[] = $row['Field'];
        }
    }

    // JSON 形式でカラム名の配列を返す
    echo json_encode($columns);
} catch (PDOException $e) {
    // エラーが発生した場合はエラーメッセージを表示
    echo json_encode(array('error' => 'Database connection error: ' . $e->getMessage()));
}
?>
