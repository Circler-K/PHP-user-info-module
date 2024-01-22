
<?php
	//session setting
	@session_start(); // sometimes it throw error
	if($_SESSION){ // if logined
		echo "<script>";
		echo "alert('Already Login!!'); history.go(-1);"; // NO access for logined user
		echo "</script>";
	}
	//주로 처음기준 *4
	//$MYSQLconnect=mysql_connect('localhost', 'root', 'apmsetup');
	//str_replace(chr(0), '', $input); 이런거 처럼 널바이트 체크해줘야할듯 str_replace 쓰지말고 preg_match로
	//mysqli_fetch_array는 select문을 이용했을 때 그 결과값을 파이썬의 딕셔너리형식으로 불러온다.
	//mysqli_query 는 ,,,,
	//mysqli_fetch_all
	global $post_data = array(
		"ID" => $_POST['ID'],
		"password" => $_POST['password'],
		"username" => $_POST['username'],
		"phone" =>$_POST['phone'],
		"age" => $_POST['age'],
		"intro" =>  $_POST['intro']
		// 6~24
		// 8자부터 32자까지 , 해싱하기 &Salting
		// 4자부터 18자까지 (한글은 strlen하면 3)
		// 정규식으로 숫자가 아니면 fail
		// 500자로 자르기
	);
	public function input_check(){
		if(!(strlen($ID)>=6 && strlen($ID)<=24)){
			return 0;
		}
		if(!(strlen($password)>=8 && strlen($password)<=32)){
			return 0;
		}
		if(!(strlen($username)<=18 && strlen($username)>=4)){
			return 0;
		}
		if(!(!preg_match('/[^0-9]/i', $phone))){
			return 0;
		}
		if(!($age>0 && $age<150)){
			return 0
		}

		//$password=substr($password,"!@#$%"); // 특수문자 Salting
		$post_data['ID'] = base64_encode($post_data['ID']);
		$post_data['password']=$post_data['password']." PLUS salting string"; // 일반 스트링 Salting
		$post_data['password']=hash('sha512',hash('sha256',$post_data['password'])); //password sha256 + sha512 로 감싸주기
		return 1;
	}
?>
<?php
	$post_data['intro'] = substr($post_data['intro'],0,500);
	$post_data['ID'] = addslashes($post_data['ID']);
	$post_data['password'] = addslashes($post_data['password']);
	$post_data['username'] = addslashes($post_data['username']);
	$post_data['phone'] = addslashes($post_data['phone']);
	$post_data['intro'] = addslashes($post_data['intro']);
	
	$check=input_check();
	if($check){
		// db연결
		require_once("DB_connect_for_join.php");
		if($DB_connect){
			$DB_SQL_query="SELECT ID from member where ID='{$$post_data['ID']}' or username='{$$post_data['username']}'";
			$result = mysqli_query($DB_connect, $DB_SQL_query);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC); //
			if($row){
				echo "<script>alert('Already ID! || Already Nick'); history.go(-1);</script>";
			}
			else{
				$DB_SQL_query="INSERT INTO member (`ID`, `password`, `username`, `phone`, `age`,`intro`) VALUES ('{$$post_data['ID']}', '{$$post_data['password']}', '{$$post_data['username']}', {$$post_data['phone']},'{$$post_data['age']}', '{$$post_data['intro']}')";
				mysqli_query($DB_connect,$DB_SQL_query) or die('query error');
				echo "<script>alert('Welcome!');</script>";
				echo "<meta http-equiv='refresh' content='0;url=/ wherever you want'>";
			}
		}
		else{
			die("Connect Failed OTL");
		}
	}
	else{
		echo "<script>alert('Worng Data!'); history.go(-1);</script>";
	}
?>