<?php

function get_dept_subject($connection){
	$DEPT_subjects = array();
	$DEPT_parameter = "DEPT";
	$DEPT_sql = "SELECT * from configuration WHERE parameter='$DEPT_parameter'";
	// echo $DEPT_sql;
	$DEPT_res = $connection->query($DEPT_sql);
	if($DEPT_res->num_rows>0){
		$DEPT_row = $DEPT_res->fetch_assoc();
		$DEPT_subjects = explode("_", $DEPT_row['value']);
	}
	return $DEPT_subjects;
}