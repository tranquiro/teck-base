<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
require('../dbconnect.php'); //データベースへ接続
session_start(); //セッションスタート
if (!empty($_POST)) { //$_POSTが空でないとき=フォームが送信されたとき
	// エラー項目の確認
	if ($_POST['name'] == '') {  //nameが空だった場合$error配列にblankを格納
		$error['name'] = 'blank';
	}
	if ($_POST['email'] == '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {  //passwordが4文字以下の場合$error配列にlengthを格納
		$error['password'] = 'length';
	}
	if ($_POST['password'] == '') {  //passwordが空の場合$error配列にblankを格納
        $error['password'] = 'blank';
    }
	// 重複アカウントのチェック
	if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}
	if (empty($error)) { //$error配列が空の時=入力項目に問題がないとき
		$_SESSION['join']=$_POST;
		header('Location: check.php');  //check.phpに遷移
		exit();  //セッション処理を終わらせる
	}
}

//書き直し
if ($_REQUEST['action']=='rewrite'){  //URLにindex.php?action=rewriteと指定された場合
	$_POST=$_SESSION['join'];  //＄_POSTにセッションのjoinを書き戻す＝フォームの内容を再現
	$error['rewrite']=true; //もう一度ファイルを指定してもらう必要があるためエラーメッセージを出すために利用
}
?>
<p>次のフォームに必要事項をご記入ください</p>
<form action="" method="post" enctype="multipart/form-data">
    <dl>
        <dt>ニックネーム<span class="required">必須</span></dt>
        <dd><input type="text" name="name" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>"/>
            <?php if ($error['name'] == 'blank'): ?>
			<p class="error">* ニックネームを入力してください</p>
			<?php endif; ?>
			<?php if ($error['name'] == 'duplicate'): ?>
			<p class="error">* 指定されたニックネームはすでに登録されています</p>
			<?php endif; ?>			
        </dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" />
        	<?php if ($error['email'] == 'blank'): ?>
			<p class="error">* メールアドレスを入力してください</p>
            <?php endif; ?>
        	<?php if ($error['email'] == 'duplicate'): ?>
			<p class="error">* 指定されたメールアドレスはすでに登録されています</p>
			<?php endif; ?>
		</dd>
		<dt>パスワード<span class="required">必須</span></dt>
		<dd><input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>"/>
            <?php if ($error['password'] == 'blank'): ?>
            <p class="error">* パスワードを入力してください</p>
			<?php endif; ?>
			<?php if ($error['password'] == 'length'): ?>
			<p class="error">* パスワードは4文字以上で入力してください</p>
			<?php endif; ?>
        </dd>
		</dl>
		<div><input type="submit" value="入力内容を確認する" /></div>
	</form>