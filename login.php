<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
require('dbconnect.php');  //データベースに接続
session_start();  //セッションスタート
if ($_COOKIE['email'] != '') {
$_POST['email'] = $_COOKIE['email'];
$_POST['password'] = $_COOKIE['password'];
$_POST['save'] = 'on';
}
if (!empty($_POST)) {  //$_POSTが空でないとき＝ログインボタンがクリックされたかどうか
	// ログインの処理
	if ($_POST['email'] != '' && $_POST['password'] != '') {  //emailとpassswordが記入されているか確認
		$login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');  //データベースから探索
			$login->execute(array(  //取り出す
				$_POST['email'],
				sha1($_POST['password'])
			));
			$member = $login->fetch();  //$memberに格納
			if ($member) {
				// ログイン成功
				$_SESSION['id'] = $member['id'];
				$_SESSION['time'] = time();

				// ログイン情報を記録する
				if ($_POST['save'] == 'on') {
				setcookie('email', $_POST['email'], time()+60*60*24*14);  //cookieに値を保存（14日間）
				setcookie('password', $_POST['password'], time()+60*60*24*14);
				}

				header('Location: posts.php'); exit();  //index.phpに遷移
			} else {
				$error['login'] = 'failed';  //データが存在しなかった場合failedを$error配列に格納
			}
		} else {
			$error['login'] = 'blank';  //emailとパスワードが記入されてなければblankを$error配列に格納
		}
	}
?>
			<div id="lead">
				<p>メールアドレスとパスワードを記入してログインしてください。</p>
				<p>入会手続きがまだの方はこちらからどうぞ。</p>
				<p>&raquo;<a href="join/">入会手続きをする</a></p>
			</div>
			<form action="" method="post">
				<dl>
					<dt>メールアドレス</dt>
					<dd>
						<input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>"/>
						<?php if ($error['login'] == 'blank'): ?>
							<p class="error">* メールアドレスとパスワードをご記入ください</p>
						<?php endif; ?>
						<?php if ($error['login'] == 'failed'): ?>
							<p class="error">* ログインに失敗しました。正しくご記入ください。</p>
						<?php endif; ?>
					</dd>
					<dt>パスワード</dt>
					<dd>
						<input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
					</dd>
					<dt>ログイン情報の記録</dt>
					<dd>
						<input id="save" type="checkbox" name="save" value="on"><label
						for="save">次回からは自動的にログインする</label>
					</dd>
				</dl>
				<div><input type="submit" value="ログインする" /></div>
			</form>
		</div>

