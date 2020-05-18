
<?php 
if(isset($_POST['dept']) && isset($_POST['year'])){
  
  $dept_ = $_POST['dept'];
  $year_ = $_POST['year'];

  require_once '../DB/config.php';
  $sql = "SELECT table_name from student_master where year=$year_";
  $res = $connection->query($sql);

  $table_name = "";
  if($res && $res->num_rows>0){
    $row=$res->fetch_assoc();
    $table_name = $row['table_name'];
  }

  $data = array();

  if($table_name!=""){
    $sql = "SELECT * from $table_name where class LIKE '$dept_%'";
    $res = $connection->query($sql);
    if($res && $res->num_rows>0){
      while($row=$res->fetch_assoc()){
        $one = array();
        array_push($one, $row['name']);
        array_push($one, $row['roll_number']);
        array_push($one, $row['class']);
        array_push($data, $one);
      }
    }
  }

  echo json_encode($data,JSON_FORCE_OBJECT);
  $connection->close();

}else{
  header('Location: ../index.php');
}

  

?>