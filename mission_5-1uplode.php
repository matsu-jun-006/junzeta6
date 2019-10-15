<?php
//データベース接続
  $dsn = 'mysql:dbname=********db;host=localhost';
  $user = '********';
  $password = 'passward';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
  $sql = "CREATE TABLE IF NOT EXISTS newfile1"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"
  . "name char(32),"
  . "comment TEXT,"
  . "date DATETIME,"
  . "pass TEXT"
  .");";
  $stmt = $pdo->query($sql);



//新規投稿
 
  if( !empty($_POST["name"])&&!empty($_POST["comment"])){

  if( !empty($_POST["edinum"])){//編集番号有りの場合
  $edinum = $_POST["edinum"];
  $edidate = date("Y/m/d H:i:s");

		$sql = "update newfile1 set name=:name,comment=:comment,date=:date where id=$edinum";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $ediname2, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $edicomm2, PDO::PARAM_STR);
		$stmt->bindParam(':date', $edidate, PDO::PARAM_STR);

  		$ediname2 = $_POST["name"];#編集後の名前
		$edicomm2 = $_POST["comment"];#編集後の番号

		$stmt->execute();

  		$sql = 'SELECT * FROM newfile1';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
			echo $row['id'].',';
			echo $row['name'].',';
			echo $row['comment'].',';
			echo $row['date'].'<br>';
		echo "<hr>";
		}



  }//番号有り終わり

  else{//番号なしの場合
  	if(!empty($_POST["pass"])){#パスワード埋まってる？

  	$sql = $pdo -> prepare("INSERT INTO newfile1 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
  	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
  	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
  	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
  	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
  	$name = $_POST["name"];
  	$comment = $_POST["comment"];
  	$date = date("Y/m/d H:i:s");
  	$pass = $_POST["pass"];
  	$sql -> execute();

  	$sql = 'SELECT * FROM newfile1';
  	$stmt = $pdo->query($sql);
  	$results = $stmt->fetchAll();
  	foreach ($results as $row){#foreachループ始まり
  		//$rowの中にはテーブルのカラム名が入る
  		echo $row['id'].',';
  		echo $row['name'].',';
  		echo $row['comment'].',';
  		echo $row['date'].'<br>';
  	echo "<hr>";
  	}#foreachループ終わり
  	}#パスワード埋まってた

  	else{#パスワードナイヨ
  	echo"！パスワ―ドを決めてください！";
  	}

  }//番号なし終わり


  }#新規投稿終わり


//削除処理
  
  elseif( !empty($_POST["delete"])&&isset($_POST["delpass"])){

  $delid = $_POST["delete"];

  $sql = "SELECT * FROM newfile1 where id=$delid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $results = $stmt->fetch();

  if($results['pass'] == $_POST["delpass"]){#パス正しい

  $sql = "delete from newfile1 where id=$delid";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $delid, PDO::PARAM_INT);
  $stmt->execute();


  	$sql = 'SELECT * FROM newfile1';
  	$stmt = $pdo->query($sql);
  	$results = $stmt->fetchAll();
  	foreach ($results as $row){
  		//$rowの中にはテーブルのカラム名が入る
  		echo $row['id'].',';
  		echo $row['name'].',';
  		echo $row['comment'].',';
  		echo $row['date'].'<br>';
  	echo "<hr>";
  	}
  }#パス正しい終わり

  else{#パス正しくない
  	echo"パスワードが違います";
  }#パス正しくない終わり

  }//削除処理終わり


//編集選択

  elseif( !empty($_POST["edit"])){
  $ediid = $_POST["edit"];

  $sql = "SELECT * FROM newfile1 where id=$ediid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $results = $stmt->fetch();

  if($results['pass'] == $_POST["edipass"]){#パス正しい

  		$editid = $results['id'];
  		$ediname = $results['name'];
  		$edicomm = $results['comment'];
  		$edidate = $results['date'];
  }#パス正しい終わり

  else{#パス正しくない
  	echo"パスワードが違います";
  }#パス正しくない終わり

  }//編集選択終わり

?>


<html lang="ja">
	<meta http-equiv="content-type" charset ="utf-8">

<!-- 投稿 -->

<form action="" method="post">
  	<input type="text" name="name" placeholder="名前"  value = "<?php if(isset($ediname)){echo $ediname;} ?>"><br>
  	<input type="text" name="comment" placeholder="コメント"  value = "<?php if(isset($edicomm)){echo $edicomm;} ?>"><br>
  	<input type="text" name="pass" placeholder="パスワード">
  	<input type="hidden" name="edinum" value="<?php if(isset($ediid)){echo $editid;} ?>">
  	<input type="submit" value="送信">
</form>


<!-- 削除 -->

<form action="" method="post">
  	<input type="text" name="delete" placeholder="削除対象番号"><br>
  	<input type="text" name="delpass" placeholder="パスワード">
  	<input type="submit" value="削除">
</form>


<!-- 編集 -->

<form action="" method="post">
  	<input type="text" name="edit" placeholder="編集対象番号"><br>
  	<input type="text" name="edipass" placeholder="パスワード">
  	<input type="submit" value="編集">

</form>


</html>