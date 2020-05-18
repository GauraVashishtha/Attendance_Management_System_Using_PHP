
<?php 
if(isset($_POST['roll_num'])){
  $roll_num_ = $_POST['roll_num'];
  // echo $roll_num_;
  require_once '../DB/config.php';

  $sql = "SELECT table_name from student_master";
  $res = $connection->query($sql);

  $class = "";
  $name = "";
  if($res && $res->num_rows>0){
    while($row=$res->fetch_assoc()){
      $sql = "SELECT * FROM ".$row['table_name']." WHERE roll_number=$roll_num_";
      // echo $sql;
      $res1 = $connection->query($sql);
      if($res1 && $res1->num_rows>0){
        $row1 = $res1->fetch_assoc();
        $class = $row1['class'];
        $name = $row1['name'];
        break;
      }

    }
  }

  $dept = "";
  $sec = "";

  if($class!=""){
   // echo $class;
    $dept = explode("_",$class)[0];
    $sec = explode("_",$class)[1];
  }

  $logs = array();
  
  if($dept!="" && $sec!="" && $name!=""){

    $sql = "SELECT log_table from teacher_master";
    $res = $connection->query($sql);
    if($res && $res->num_rows>0){
      while($row=$res->fetch_assoc()){
        $log_table = $row['log_table'];

        $sql = "SELECT * from $log_table WHERE sheet_table LIKE '%_%_".$dept."_%_".$sec."'";
        // echo $sql; 
        $res1 = $connection->query($sql);
        if($res1 && $res1->num_rows>0){
          while($row1=$res1->fetch_assoc()){
            array_push($logs, $row1['sheet_table']);
          }
        }

      }
    }
  }

  // print_r($logs);

  // echo $name;
  $all_data = array();

  if(!isset($_POST['det_lis'])){
    array_push($all_data, $name);
  }

  foreach ($logs as $log) {
    $data = array();

    $sql = "SHOW COLUMNS IN $log LIKE 'R_".$roll_num_."' ";
    $res = $connection->query($sql);

    $att = 0;
    $tot=0;
    $log_data = array();
    
    if($res && $res->num_rows>0){
      $roll_col = 'R_'.$roll_num_;
      $sql = "SELECT curr_date,period,$roll_col from $log";
      $res = $connection->query($sql);
      if($res && $res->num_rows>0){
        while($row=$res->fetch_assoc()){
          // echo $log." ".$row['curr_date']." ".$row['period']." ".$row[$roll_col]."<hr>";
          if($row[$roll_col]=='1')
            $att++;

          $tot ++;

          $temp_data = array();
          array_push($temp_data,$row['curr_date']);
          array_push($temp_data,$row['period']);
          array_push($temp_data,$row[$roll_col]);

          array_push($log_data, $temp_data); 
        }
      }
    }

    if(isset($_POST['det_lis']) && $_POST['det_lis']==1){
      if($att/$tot<0.75){
        
        if(empty($all_data)){
          array_push($all_data, $name);
          array_push($all_data, $roll_num_);
          array_push($all_data, $dept);
          array_push($all_data, $sec);
        }

        array_push($data, $log);
        array_push($data, $att);
        array_push($data, $tot);        
      }
    }else{
      array_push($data, $log);
      array_push($data, $att);
      array_push($data, $tot);
      array_push($data, $log_data);    
    }
  
    if(!empty($data))
      array_push($all_data, $data);
  }


  echo json_encode($all_data,JSON_FORCE_OBJECT);
  // echo '<pre>';
  // print_r($all_data);
  // echo '</pre>';
  
  $connection->close();

}else{
  header('Location: ../index.php');
}

  

?>