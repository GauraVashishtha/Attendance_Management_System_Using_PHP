<?php
    session_start();
    if(isset($_SESSION['department']) )
        $department_ = $_SESSION['department'];
    else
        header("Location: ../index.php");

?>



<?php

date_default_timezone_set('UTC');
   if(isset($_FILES['st_data'])){
      $errors= array();
      $file_name = $_FILES['st_data']['name'];
      $file_size =$_FILES['st_data']['size'];
      $file_tmp =$_FILES['st_data']['tmp_name'];
      $file_type=$_FILES['st_data']['type'];
      $department = $_POST['department'];
      $year = $_POST['year'];

      // $file_ext=strtolower(end(explode('.',$_FILES['st_data']['name'])));
      $file_ext=explode('.',$_FILES['st_data']['name']);
      $file_ext = $file_ext[count($file_ext)-1];

      $extensions= array("xlsx");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a xlsx file.";
      }else{
         
         if($file_size > 2097152){
            $errors[]='File size must be excately 2 MB';
         }
         else{
                require_once('../DB/config.php');

                  $sql = "SELECT table_name from Student_Master WHERE year = ".$year;

                  $result = $connection->query($sql);
                  $table_name = "";
                  if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                        if($table_name!=""){
                           $errors[] = "Error multiple year entry in table, contact admin";
                           break;
                        }
                        $table_name = $row["table_name"];
                      }
                  } else {
                     $curr_year = date("Y");
                     $table_name = "year_".$curr_year."_".$year;
                     $sql = "CREATE TABLE IF NOT EXISTS ".$table_name."
                                       (
                                                 name varchar(100) NOT NULL,
                                                 roll_number varchar(100) NOT NULL,
                                                 class varchar(100) NOT NULL,
                                                 PRIMARY KEY (roll_number) 
                                       );
                              ";
                     // echo $sql;
                     if (!$connection->query($sql)){
                         $errors[] = "Error creating table: " . $connection->error;
                     }

                     if(empty($errors)){
                        $sql = "INSERT INTO Student_Master VALUES ('".$year."','".$table_name."');";
                        if (!$connection->query($sql)){
                            $errors[] = "Error creating table: " . $connection->error;
                        }
                     }
                  }

            if(empty($errors)==true){
               // $file_name = str_replace($file_ext, "csv", $file_name);
               move_uploaded_file($file_tmp,"st_datas/".$file_name);

               if($file_ext == "xlsx"){

                  require('../Library/xlsx_reader.php');

                  // Library to extract xlsx file
                  $xlsx = new XLSXReader("./st_datas/".$file_name);
                  $sheetNames = $xlsx->getSheetNames();

                  // Sheet Names
                  $section = 0;
                  foreach($sheetNames as $sheetName) {
                     $sheet = $xlsx->getSheet($sheetName);

                     $entered = 0;

                        foreach($sheet->getData() as $row) {
                           $student_name = "";
                           $student_roll_num = "";
                           $student_class = "";

                           $check = 0;

                           foreach ($row as $cell) {
                              if($cell==NULL || $check >= 4)
                                 break;
                              $check++;
                           }

                           if($check!=3 && $entered==1)
                              $entered = 0;
                           

                           if($check==3 && $entered==0){
                              $section++;
                              $entered = 1;
                           }

                           if($check == 3){
                              $k = 0; 
                              foreach($row as $cell) {
                                 if($k==1)
                                    $student_roll_num = $cell;
                                 if($k==2)
                                    $student_name = $cell;
                                 $k++;
                              }
                              $student_class =  $department."_".$section;
                              $sql = "INSERT INTO ".$table_name." VALUES ('".$student_name."','".$student_roll_num."','".$student_class."');";
                              // echo $sql;
                              if (!$connection->query($sql)){
                                 $errors[]= "You have already added this data";
                                 break;
                                 // exit;
                                 $get_out = 1;
                              }

                           }
                           // echo $section."<hr>";
                        }
                    if(isset($get_out) && $get_out==1){
                      break;
                    }
                  }

               }
               if(empty($errors)==True){
                  $errors[]= "All Data Inserted successfully";

                  // echo $year." ".$section." ".$department;

                  $value = "";
                  for($s=1;$s<$section;$s++){
                    $value .= $s."_";
                  }
                  $value .= $section;

                  $parameter = $department."_".(2*$year)."_sec";
                  $sql = "INSERT INTO `configuration`(`parameter`, `value`) VALUES ('$parameter','$value')";
                  $res = $connection->query($sql);

                  $parameter = $department."_".((2*$year)-1)."_sec";
                  $sql = "INSERT INTO `configuration`(`parameter`, `value`) VALUES ('$parameter','$value')";
                  $res = $connection->query($sql);
                  
               }

            }else{
               // print_r($errors);
            }

               // print_r($errors);
            $connection->close();
         }
      } 

      $_SESSION['popUp'] = $errors[count($errors)-1];
      // To unset all values
      // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
       header('Location: ./index.php');
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assests/css/lib3.min.css">
    <script type="text/javascript" src="../assests/js/jlib.min.js"></script>
    <script type="text/javascript" src="../assests/js/lib3.min.js"></script>
    <style type="text/css">
        .wrapper{
            /*width: 650px;*/
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
  </style>
  <link rel="stylesheet" type="text/css" href="../assests/css/main.css">
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>

    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>                        
          </button>
          <a class="navbar-brand" href="#">Logo</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li class="active"><a href="./index.php">Home</a></li>
            
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../index.php"><span class="glyphicon glyphicon-log-in"></span> LogOut </a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="page-header ">
                        <h2>Add Student</h2>
                    </div>
                    <p>Please fill this form and submit to add student record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"  enctype="multipart/form-data" class="inner_form">

                        <div class="form-row">
                            <div class="col-md-4">
                                <label>Year</label>
                                <select name="year" class="form-control">
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Department</label>
                                <select name="department" class="form-control">
                                <?php
                                  require_once '../DB/config.php';
                                  require_once '../config/get_department.php';

                                      $dept_subject = get_dept_subject($connection);
                                      if($department_!='*'){
                                        echo '<option value="'.$department_.'">'.$department_.'</option>';
                                      }
                                      else{
                                        foreach ($dept_subject as $sub) {
                                          echo '<option value="'.$sub.'">'.$sub.'</option>';
                                        }
                                      }
                                  $connection->close();
                                  ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>XLSX File</label>
                                <input type="file" name="st_data" value="Input xlsx file" class="form-control"/>
                                <span class="help-block">File must have [S.No.,Roll No.,Name] format for student entry.</span>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-info" value="Submit">
                        <a href="./index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>  
</body>
</html>