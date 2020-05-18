<?php



if(isset($_POST) && isset($_POST['dept']) && isset($_POST['sem'])){
	// echo $_POST['dept'],$_POST['sem'];
	$parameter = $_POST['dept']."_".$_POST['sem']."_sub";
	require_once '../DB/config.php';
	$sql = "SELECT * from configuration WHERE parameter='$parameter'";
	// echo $sql;
	$res = $connection->query($sql);
	if($res->num_rows>0){
		$row = $res->fetch_assoc();
		echo json_encode(explode("_",  $row['value']),JSON_FORCE_OBJECT);
	}
	$connection->close();
}else{
	header("Location: ../index.php");
}