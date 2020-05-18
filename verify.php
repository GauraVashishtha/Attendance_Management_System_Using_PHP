<?php 
	session_start();
	if(isset($_GET['h'])){
		$hash = $_GET['h'];

		require_once './DB/config.php';
		$sql = 'select * from student_user';
		$res = $connection->query($sql);
		while($row = $res->fetch_assoc()){
			$email = $row['e_mail'];
			$password = $row['pass'];
			$roll_no = $row['roll_number'];

			$data = md5($roll_no).md5($password).md5($email);


			if($data == $hash){
				if($row['verified']==0){
					$sql1 = "UPDATE `student_user` SET `verified`='1' WHERE `roll_number`='$roll_no' and `pass`='$password' and `e_mail`='$email'";
					// echo $sql1;
					$res1 = $connection->query($sql1);
					if(!$res1){
						$_SESSION['message'] = "Some error occured, please try again later";
					}else{
						$_SESSION['message'] = "Please proceed to log in.";
					}
				}else{
					$_SESSION['message'] = "Already verified, please proceed to login";
				}
				break;
			}
		}
	}

	header('Location: ./index.php');
?>