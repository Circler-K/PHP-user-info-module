<?php
	//세션설정
	@session_start();
	if($_SESSION){
		echo '<script>';
		echo "alert('Already Login!!'); history.go(-1);";
		echo '</script>';
	}
?>
<?php
	
	$ID=base64_encode($_POST['ID']);
	$password=$_POST['password']." PLUS salting string"; //PLUS salting
	$password=hash('sha512',hash('sha256',$password));
	require_once("DB_connect_for_login.php");
	if($DB_connect){
		$DB_SQL_query="select * from member where id='{$ID}' AND password='{$password}'";
		$result = mysqli_query($DB_connect, $DB_SQL_query);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($row){
			$_SESSION['ID']=base64_decode($row['ID']);
			$_SESSION['username']=$row['username'];
			$_SESSION['phone']=$row['phone'];
			$_SESSION['age']=$row['age'];
			
			echo "<script>alert('hello!');</script>";
			echo "<meta http-equiv='refresh' content='0;url=/ wherever you want'>";
		}
		else{
			echo "<script>alert('No Account!!'); history.go(-1);</script>";
		}
	}
	
?>