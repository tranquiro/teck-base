<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>DB＋PHP</title>
</head>
<!--valueで初期値を設定（placeholderの方が好み）-->
<body>
    <?php
    // DB接続設定
	$dsn = ʼデータベース名ʼ;
	$user = ʼユーザー名ʼ;
	$password = ʼパスワードʼ;
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
     
	
    //CREATE文：データベース内にテーブルを作成	
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass TEXT,"
	. "postdate DATETIME"
	.");";
	$stmt = $pdo->query($sql);  
	
	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";
	
	$sql ='SHOW CREATE TABLE tbtest';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";
	
     //フォーム内が空でない場合に以下を実行する
	if (!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass'])) {
	if (empty($_POST['editNO'])){ 
	
	//新規投稿機能
	$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, pass, postdate) VALUES (:name, :comment, :pass, now());");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	$name = $_POST["name"];
	$comment = $_POST["comment"];//好きな名前、好きな言葉は自分で決めること
	$pass = $_POST["pass"];
	$sql -> execute();
	}else{
	    
	//編集機能
	$sql = 'UPDATE tbtest SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$id = $_POST["editNO"]; //変更する投稿番号
	$name = $_POST["name"];
	$comment =$_POST["comment"];  //変更したい名前、変更したいコメントは自分で決める
	$pass = $_POST["pass"];
	$stmt->execute();
	}
	}
	
	//削除機能
	//削除番号、削除パスワードを空でないとき
	if (!empty($_POST['dnum']) && !empty($_POST["delpass"])) {
	$id = $_POST["dnum"];
	$sql = 'SELECT * FROM tbtest WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    $deletepass = $row['pass'];
    if($deletepass==$_POST["delpass"]){
	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
    }
	}
	}
	//編集選択機能

     //編集フォームの送信の有無で処理を分岐
      if (!empty($_POST["edit"]) && !empty($_POST["editpass"])) {
          //入力データの受け取りを変数に代入
          $id = $_POST["edit"]; // idがこの値のデータだけを抽出したい、とする
          $sql = 'SELECT * FROM tbtest WHERE id=:id ';
          $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
          $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
          $stmt->execute();                             // ←SQLを実行する。
          $results = $stmt->fetchAll(); 
          foreach ($results as $row){
              $editpass=$row["pass"];
          if($editpass==$_POST["editpass"]){
          $sql = 'SELECT * FROM tbtest WHERE id=:id ';
          $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
          $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
          $stmt->execute();                             // ←SQLを実行する。
          $results = $stmt->fetchAll(); 
          foreach ($results as $row){
          //$rowの中にはテーブルのカラム名が入る
           $editnumber = $row['id'];
           $editname = $row['name'];
           $editcomment = $row['comment'];
           $editpass=$row['pass'];
        //既存の投稿フォームに、上記で取得した「名前」と「コメント」の内容が既に入っている状態で表示させる
        //formのvalue属性で対応
            
        }
          }
      }
      }

    ?>
    <h1>投稿フォーム</h1>
    <table border="1">
    <tr>
    <form action="mission_5-1.php" method="post">
      <td>
      <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>">
      </td>
      <td>
      <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>">
      </td>
      <td>
      <input type="text" name="pass" placeholder="パスワード" value="<?php if(isset($editpass)) {echo $editpass;} ?>">
      </td>      
      <td>
      <input type="text" name="editNO" placeholder="編集時対象番号" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
      </td>
      
      <td>
      <input type="submit" name="submit" value="送信">
      </td>
    </form>
    </tr>
    
    <tr>
    <form action="mission_5-1.php" method="post">
      <td>
      <input type="text" name="dnum" placeholder="削除対象番号">
      </td>
      <td>
      <input type="text" name="delpass" placeholder="パスワード">
      </td>
      <td>
      <input type="submit" name="delete" value="削除">
      </td>
    </form>
    </tr>
    
    <tr>
    <form action="mission_5-1.php" method="post">
      <td>
      <input type="text" name="edit" placeholder="編集対象番号">
      </td>
      <td>
      <input type="text" name="editpass" placeholder="パスワード">
      </td>
      <td>
      <input type="submit" value="編集">
      </td>
    </form>
    </tr>
    </table>
    <br>

    <h1>投稿内容</h1>
    <?php       
	//表示
	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['pass'].',';
		echo $row['postdate']."<br>";
	echo "<hr>";
	}
    ?>
    <br>
</body>
</html>