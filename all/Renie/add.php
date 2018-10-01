<?php
	header('Content-type:text/html; charset=utf-8');

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname="renie";
	
	
	$bgcolor = "";
	$like = 0;
	
	try{
		if(!array_key_exists("title",$_POST)){
			throw new Exception("title is null");
		}
		else{
			$title = $_POST["title"];
		 }
		  if(!array_key_exists("content",$_POST)){
			throw new Exception("content is null");
		  }
		  $content = $_POST["content"];
		 $date = date('Y-m-d H:i:s');
		 if(array_key_exists("author",$_POST)){
			$author = $_REQUEST['author']; 
		 }
		 if(array_key_exists("bgcolor",$_POST)){
			$bgcolor = $_REQUEST['bgcolor']; 
		 }
		 
		  // 创建连接
			$conn = new mysqli($servername, $username, $password,$dbname);
			mysqli_set_charset($conn,'utf8');
			// 检测连接
			if (!$conn) {
				die("连接失败: " .  mysqli_connect_error());
			} 
			//echo "连接成功";

			$sql = "INSERT INTO note (title, content, date,author,`like`,bgcolor)
						VALUES ('$title', '$content ','$date','$author','$like','$bgcolor')";
			if (mysqli_query($conn, $sql)) {
				//立即跳转至目标页面
				echo "<script>window.location.href='success.html';</script>";;
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
				echo "false";
			}
			mysqli_close($conn);
	}catch(Exception $e){
		echo $e;
		echo "false";
	}

?>
