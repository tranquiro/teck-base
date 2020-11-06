<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>出会い Folder</title>
    <link rel="stylesheet" href="1104.css">
</head>
<!--valueで初期値を設定（placeholderの方が好み）-->
<body>
  <?php
  session_start();
  require('dbconnect.php');
  if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {  //ログインしているかどうか=idがセッションに記録＋最後の行動から1時間以内
	// ログインしている
	$_SESSION['time'] = time();  //今の時間で上書き
	$members = $db->prepare('SELECT * FROM members WHERE id=?');  //データベースから会員情報を探索
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	// ログインしていない
	header('Location: login.php');
	exit();
}
// DB接続設定
	$dsn = 'mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザー名';
	$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//CREATE文：データベース内にテーブルを作成
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "namae TEXT,"
. "istu TEXT,"
. "doko TEXT,"
. "comment TEXT,"
. "postdate DATETIME"
.");";
$stmt = $pdo->query($sql);  
//フォーム内が空でない場合に以下を実行する
if (!empty($_POST["comment"])) {
//新規投稿機能
$sql = $pdo -> prepare("INSERT INTO tbtest (namae,istu,doko,comment,postdate) VALUES (:namae,:istu,:doko,:comment,now());");
$sql -> bindParam(':namae', $namae, PDO::PARAM_STR);
$sql -> bindParam(':istu', $istu, PDO::PARAM_STR);
$sql -> bindParam(':doko', $doko, PDO::PARAM_STR);
$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
$namae = $_POST["namae"];
$comment = $_POST["comment"];//好きな名前、好きな言葉は自分で決めること
$istu = $_POST["istu"];
$doko = $_POST["doko"];
$sql -> execute();
} 
	//削除機能
	//削除番号、削除パスワードを空でないとき
	if (!empty($_POST['dnum'])) {
	$id = $_POST["dnum"];
	$sql = 'SELECT * FROM tbtest WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    if($deletepass==$_POST["delpass"]){
	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
    }
	}
?>
<center>
<div class="form">
<header>
    <div style="text-align: right"><a href="logout.php">ログアウト</a></div>
      <dt><?php echo htmlspecialchars($member['name'], ENT_QUOTES); ?>さん、ようこそ！！！出会いを記録しよう！！</dt>
</header>
<h1>名刺フォーム</h1>
<form action="posts.php" method="post">
<table border="1">
<tr><td><input type="name" name="namae" placeholder="名前"></td></tr>
<tr><td><input type="text" name="istu" placeholder="いつ"></td></tr>
<tr><td><input type="text" name="doko" placeholder="どこで"></td></tr>
<tr><td><textarea type="text" name="comment" placeholder="どんな人" ></textarea></td><tr>
</table>
<br>
<input type="submit" name="submit" value="登録">
<br>
</form>
<hr>
</center>
</div>
  <?php       
	//表示
	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();
  ?>
  <div class="home">
  <center>
  <h1>My 出会い Folder</h1>
    <form action="posts.php" method="post">
    <p>
    <input type="text" name="dnum" placeholder="削除対象番号">
    <input type="submit" name="delete" value="削除"></P>
    </form>
</center>
  <div class="container">
  <?php foreach ($results as $row):?>
  <table border="2">
  <tr><td>ID:</td><td><?="{$row['id']}<br>" ?></td></tr>
  <tr><td>名前:</td><td><?="{$row['namae']}<br>" ?></td></tr>
  <tr><td>いつ:</td><td><?="{$row['istu']}<br>" ?></td></tr>
  <tr><td>どこで:</td><td><?="{$row['doko']}<br>" ?></td></tr>
  <tr><td>どんな人:</td><td><?="{$row['comment']}<br>" ?></td></tr>
  <tr><td>投稿日時:</td><td><?="{$row['postdate']}<br>" ?></td></tr>
  </table>
  <?php endforeach; ?>
  </div>
</div>
<footer>
  <div class="wrapper">
  <p>Copyright 2020 Taisei Hosono</p>
  </div>
</footer>
</body>
</html>