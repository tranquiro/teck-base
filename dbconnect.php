<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
try {
    $dsn = 'mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザー名';
	$password = 'パスワード';
    $db = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //CREATE文：データベース内にテーブルを作成	
    $db->exec("CREATE TABLE IF NOT EXISTS members"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name VARCHAR(255),"
    . "email VARCHAR(255),"
    . "password VARCHAR(100),"
    . "created DATETIME,"
    . "modified TIMESTAMP"    
    .");");
} catch (PDOException $e) {
    echo 'DB接続エラー： ' . $e->getMessage();
}
?>