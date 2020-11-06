<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();  //セッションスタート
require('../dbconnect.php');  //データベースに接続
if(!isset($_SESSION['join'])){  //$_SESSION['join']が空の時
	header('Location: index.php'); //index.phpに遷移
	exit();
}
if (!empty($_POST)) {  //$_POSTが空でないとき
	// 登録処理をする
	$statement = $db->prepare('INSERT INTO members SET name=?,email=?,password=?,created=NOW()');
		echo $ret = $statement->execute(array(  //executeでデータベースに実行
			$_SESSION['join']['name'],
			$_SESSION['join']['email'],
			sha1($_SESSION['join']['password']), //セッションに保存した値をセット（sha1で暗号化）
		));
		unset($_SESSION['join']);  //入力情報を削除（既にデータベースに登録済み）
		header('Location: thanks.php'); //thanks.phpに遷移
		exit();
	}
?>
<form action="" method="post">
        <input type="hidden" name="action" value="submit" />
		<dl>
		<dt>ニックネーム</dt>
		<dd>
		<?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?>
		</dd>
		<dt>メールアドレス</dt>
		<dd>
		<?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES); ?>
        </dd>
		<dt>パスワード</dt>
		<dd>
		【表示されません】
		</dd>
		</dl>
		<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
	</form>
