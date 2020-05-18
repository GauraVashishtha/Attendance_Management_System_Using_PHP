<?php



if(isset($_POST) && isset($_POST['dept']) && isset($_POST['sec']) && isset($_POST['sem'])){
	// echo $_POST['dept'],$_POST['sem'];
	$dept = $_POST['dept'];
	$sec = $_POST['sec'];
	$class = $dept."_".$sec;
	$sem = $_POST['sem'];

	if($sem=="1" || $sem=="2") $sem = "1";
	else if($sem=="3" || $sem=="4") $sem = "2";
	else if($sem=="5" || $sem=="6") $sem = "3";
	else $sem = "4";


	require_once '../DB/config.php';

	$sql = "SELECT table_name from student_master WHERE year='$sem'";
	$res = $connection->query($sql);
	if(!$res){die('connection Error'. $connection->error);}

	$row = $res->fetch_assoc();
	$table_name = $row['table_name'];


	$sql = "SELECT * from $table_name WHERE class='$class'";
	// echo $sql;
	$res = $connection->query($sql);
	if($res->num_rows>0){
		$data = array();
		while($row = $res->fetch_assoc()){
			array_push($data, array($row['name'],$row['roll_number']));
		}
		
		echo json_encode($data,JSON_FORCE_OBJECT);

	}
	$connection->close();
}else{
	echo "NO";
}