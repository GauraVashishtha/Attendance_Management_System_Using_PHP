<?php 
  session_start();
  if (isset($_SESSION['time_table']) && isset($_SESSION['log_table']) && isset($_SESSION['name'])  && isset($_SESSION['user_id']) && isset($_POST['tableName']) ){
      $time_table_ = $_SESSION['time_table'];
      $log_table_ = $_SESSION['log_table'];
      $name_ = $_SESSION['name'];
      $user_id_ = $_SESSION['user_id'];
      $table_name_ = $_POST['tableName'];
  }else{
    header('Location: ../index.php');
  }
  // $table_name_ = 'nsc12345_itir13_it_1_1';

  function get_name($roll_number,$table_name_,$connection){
    $year = explode("_", $table_name_);
    $year = $year[count($year)-2];
    $st_sql = "SELECT table_name from student_master WHERE year='$year'";
    $st_res = $connection->query($st_sql);
    if(!$st_res){die("Connection Error".$connection->error);}
    $st_res = $st_res->fetch_assoc();
    $st_res = $st_res['table_name'];
    $st_sql = "SELECT name from $st_res WHERE roll_number='$roll_number'";
    $st_res = $connection->query($st_sql);
    if(!$st_res){die("Connection Error".$connection->error);}
    $st_res = $st_res->fetch_assoc();
    return $st_res['name'];
  }


  // Include config file
  require_once "../DB/config.php";
  
  // Attempt select query execution
  $sql = "SELECT * FROM $table_name_";
  $curr_idx = 1;

  $all_data = array();


  if($result = $connection->query($sql)){
      if($result->num_rows > 0){

          $sql1 = "SHOW COLUMNS FROM $table_name_";
          $res1 = $connection->query($sql1);
          $cols = array();
          if(!$res1){die("Connection Error".$connection->error);}
          while($row1 = $res1->fetch_assoc()){
            array_push($cols, $row1['Field']);
          }

          foreach ($cols as $k) {
            $temp = array();
            $temp1 = explode("_",$k);
            $temp1 = $temp1[count($temp1)-1];

            array_push($temp,get_name($temp1,$table_name_,$connection));
            array_push($temp,$temp1);
            array_push($all_data, $temp);
          }

          while($row = $result->fetch_array()){
            for($i=0;$i<count($cols);$i++){
              array_push($all_data[$i],$row[$cols[$i]]);
            }
          }

          array_push($all_data[0], "Total");
          array_push($all_data[1], "Percentage");
          for($i=2;$i<count($all_data);$i++){
            $so_far = 0;
            for($j=2;$j<count($all_data[$i]);$j++){
              $so_far += $all_data[$i][$j];
            }
            $so_far = ($so_far / (count($all_data[$i])-2))*100; 
            $so_far = number_format((float)$so_far, 2, '.', '');
            array_push($all_data[$i], $so_far);
          }
      }
  }

// echo '<pre>';
// print_r($all_data);
  echo json_encode($all_data,JSON_FORCE_OBJECT);
// echo "</pre>";
$connection->close();

?>