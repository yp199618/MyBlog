<?php
	header('Content-type:text/html; charset=utf-8');

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname="renie";
	 
	 



	 
	// 创建连接
	$conn = new mysqli($servername, $username, $password,$dbname);
	mysqli_set_charset($conn,'utf8');
	// 检测连接
	if (!$conn) {
		die("连接失败: " . mysqli_connect_error());
	} 
	//echo "连接成功";
	$sql = "select * from note ORDER BY id DESC";
	
	$notes = mysqli_query($conn, $sql);
	
	//echo json_encode($notes);
	if(mysqli_num_rows($notes) > 0){
		$arr=array();//开始转换
		while ($note = mysqli_fetch_assoc($notes)) {
			$arr[] = $note;
		}
		echo json_encode($arr);
		//echo "666";
		while($note = mysqli_fetch_assoc($notes)){
			echo "note 标题：" . $note["title"];
		}
	}
	else{
		echo "查无结果";
		
	}
	mysqli_close($conn); 
	
	
?>
