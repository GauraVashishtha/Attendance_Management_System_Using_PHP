<?php
    session_start();
    if(isset($_SESSION['department']) && isset($_POST['userId'])){
        $department_ = $_SESSION['department'];
        $userId_ = $_POST['userId'];
    }
    else
        header("Location: ../index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<?php


$day_err = "Required";$day = "";
$period_err = "Required";$period = "";
$department_err = "Required";$department = "";
$semester_err = "Required";$semester = "";
$subject_err = "Required";$subject = "";
$sections_err = "Please Select a Section";$sections = "";
$xl_file_err = "If you uploaded a file here, then all other fields will be ignored";



  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['department']) && isset($_POST['day']) && isset($_POST['period']) && isset($_POST['semester']) && isset($_POST['subject']) && isset($_POST['sections']) ) { 

        if(empty($_POST['department'])){
            $department_err = "Please Selct a department";
        }else{
            $department = test_input($_POST['department']);
            $department_err = "";
        }
        if(empty($_POST['day'])){
            $day_err = "Please Selct a day";
        }else{
            $day = test_input($_POST['day']);
            $day_err = "";
        }
        if(empty($_POST['period'])){
            $period_err = "Please Selct a period";
        }else{
            $period = test_input($_POST['period']);
            $period_err = "";
        }
        if(empty($_POST['semester'])){
            $semester_err = "Please Selct a semester";
        }else{
            $semester = test_input($_POST['semester']);
            $semester_err = "";
        }
        if(empty($_POST['subject'])){
            $subject_err = "Please Selct a subject";
        }else{
            $subject = test_input($_POST['subject']);
            $subject_err = "";
        }
        if(empty($_POST['sections'])){
            $sections_err = "Please Selct a section";
        }else{
            $sections = $_POST['sections'];
            $sections_err = "";
        }
  }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_FILES['st_data'])){
          $file_name = $_FILES['st_data']['name'];
          $file_size =$_FILES['st_data']['size'];
          $file_tmp =$_FILES['st_data']['tmp_name'];
          $file_type=$_FILES['st_data']['type'];
          $file_ext=explode('.',$_FILES['st_data']['name']);
          $file_ext = $file_ext[count($file_ext)-1];

          $extensions= array("xlsx");
          // echo($file_ext);

          if(in_array($file_ext,$extensions)=== false){
            $xl_file_err ="extension not allowed, please choose a xlsx file.";
          }else{
            $xl_file_err = "";
          }

        }
    }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if($department_err =="" &&
        $day_err =="" &&
        $period_err =="" &&
        $semester_err =="" &&
        $subject_err ==""
      ){

        // echo "No Error\n";



       // echo ($department."\n");
       //  echo ($day."\n");
       //  echo ($period."\n");
       //  echo ($semester."\n");
       //  echo ($subject."\n");
        $section_list = "";
        foreach ($sections as $section) {
            $section_list .= $section.",";
        }
        // echo ($section_list."\n");


        // Include config file
        require_once "../DB/config.php";

        // Attempt select query execution
        $sql = "SELECT time_table FROM teacher_master WHERE user_id='$userId_'";
        $table_name = NULL;
        if($result = $connection->query($sql)){
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $table_name = $row['time_table'];
                $result->free();
            } else{
                die("No records were found.");
            }
        } else{
            die("ERROR: " . $connection->error);
        }

        // echo $table_name;

        $sql = "INSERT INTO $table_name VALUES ('$day','$period','$semester','$subject','$department','$section_list');";
        if(!$connection->query($sql)){
            echo    '<div class="container">
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Error!</strong>Duplicate Entry
                        </div>
                    </div>';
        }
        else{
            $day_err = "Required";$day = "";
            $period_err = "Required";$period = "";
            $department_err = "Required";$department = "";
            $semester_err = "Required";$semester = "";
            echo    '<div class="container">
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong>Entry Registered
                        </div>
                    </div>';
        }
    // Close connection
    // $connection->close();
    }else if($xl_file_err==""){

        move_uploaded_file($file_tmp,"tt_datas/".$file_name);
        require_once('../Library/xlsx_reader.php');
        require_once '../DB/config.php';
        // Attempt select query execution
        $sql = "SELECT time_table FROM teacher_master WHERE user_id='$userId_'";
        $table_name = NULL;
        if($result = $connection->query($sql)){
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $table_name = $row['time_table'];
                $result->free();
            } else{
                die("No records were found.");
            }
        } else{
            die("ERROR: " . $connection->error);
        }


        // Library to extract xlsx file
        $xlsx = new XLSXReader("./tt_datas/".$file_name);
        $sheetNames = $xlsx->getSheetNames();

        $tt_ip = array();
        // echo "<pre>";
        
        foreach($sheetNames as $sheetName) {
            $sheet = $xlsx->getSheet($sheetName);
            $data = $sheet->getData();
            $row = (count($data));
            $col = (count($data[1]));
            if($row!=6){
                $xl_file_err = "No. of days should be 5";
                break;
            } 
            if($col!=8){
                $xl_file_err = "No. of periods should be 7";
                break;
            }
            // echo "<pre>";
            $tt_ip = array();
            for ($i=1; $i < $col && $xl_file_err==""; $i++) { 
                for ($j=1; $j < $row && $xl_file_err==""; $j++) {
                    if(!$data[$j][$i])
                        continue;
                    // echo $data[$j][$i]."|    ";

                    $tt_1 = $data[$j][$i];
                    $tt_1 = explode(" ", $tt_1);
                        $tt_sub = $tt_1[0];
                    $tt_1 = $tt_1[count($tt_1)-1];
                    $tt_1 = explode("-", $tt_1);
                        $tt_dept = $tt_1[0];
                        $tt_sec = $tt_1[count($tt_1)-1];


                    $tt_sql = "SELECT `parameter` FROM `configuration` WHERE value LIKE '%$tt_sub%' and parameter LIKE '$tt_dept%'";
                    // echo $tt_sql."<br>";
                    $tt_res = $connection->query($tt_sql);
                    if(!$tt_res){$xl_file_err = $connection->error;}
                    $tt_row = $tt_res->fetch_assoc();
                    // var_dump($tt_row);
                    $tt_sem = explode("_",$tt_row['parameter'])[1];
                    // print_r($tt_sem);

                    // echo ("Day ".$j);
                    // echo (" Period ".$i);

                    // echo (" SUB ".$tt_sub);
                    // echo (" DEPT ".$tt_dept);
                    // echo (" SEM ".$tt_sem);
                    // echo (" SEC ".$tt_sec." |    ");

                    // $sql = "INSERT INTO $table_name VALUES ('$day','$period','$semester','$subject','$department','$section_list');";
                    $sql = "INSERT INTO $table_name VALUES ('$j','$i','$tt_sem','$tt_sub','$tt_dept','$tt_sec');";
                    if(!$connection->query($sql)){
                        $xl_file_err = "Duplicate Entry";
                    }
                // array_push($tt_ip, $data[$j][$i]);
                }
               // print_r($tt_ip);
               // $tt_ip = array();
                // echo "<hr>";
            }

            // echo "</pre>";

            break;
        }



        if($xl_file_err!=""){
            echo    '<div class="container">
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Error!</strong>'.$xl_file_err.'
                        </div>
                    </div>';
        }
        else{
            $day_err = "Required";$day = "";
            $period_err = "Required";$period = "";
            $department_err = "Required";$department = "";
            $semester_err = "Required";$semester = "";
            echo    '<div class="container">
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong>Entry Registered
                        </div>
                    </div>';
        }
  }

?>

<div class="container">   
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Teachers Schedule </h2>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" >
                        <input type='hidden' name='userId' value='<?=$userId_?>'/>

                        <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#f_ip_1">Time Table Via Fields</button>    
                        <div id="f_ip_1" class="collapse">
                            <div class="form-row">
                                <div class="col-md-4 <?php echo (!empty($day_err) && $day_err!='Required') ? 'has-error' : ''; ?>" style="padding: 10px;overflow: hidden;">
                                    <label class="col-form-label">Day</label>
                                    <select name="day" class="form-control" >
                                      <option value="1" <?php if($day=="1") echo "selected";?> >Monday</option>
                                      <option value="2" <?php if($day=="2") echo "selected";?>>Tuesday</option>
                                      <option value="3" <?php if($day=="3") echo "selected";?>>Wednesday</option>
                                      <option value="4" <?php if($day=="4") echo "selected";?>>Thursday</option>
                                      <option value="5" <?php if($day=="5") echo "selected";?>>Friday</option>
                                    </select>
                                    <span class="help-block"><?php echo $day_err;?></span>
                                </div>
                                <div class="col-md-4 <?php echo (!empty($period_err) && $period_err!='Required') ? 'has-error' : ''; ?>" style="padding: 10px;overflow: hidden;">
                                    <label>Period</label>
                                    <select name="period" class="form-control">
                                      <option value="1" <?php if($period=="1") echo "selected";?>>1</option>
                                      <option value="2" <?php if($period=="2") echo "selected";?>>2</option>
                                      <option value="3" <?php if($period=="3") echo "selected";?>>3</option>
                                      <option value="4" <?php if($period=="4") echo "selected";?>>4</option>
                                      <option value="5" <?php if($period=="5") echo "selected";?>>5</option>
                                      <option value="6" <?php if($period=="6") echo "selected";?>>6</option>
                                      <option value="7" <?php if($period=="7") echo "selected";?>>7</option>
                                    </select>
                                    <span class="help-block"><?php echo $period_err;?></span>
                                </div>
                                <div class="col-md-4 <?php echo (!empty($department_err) && $department_err!='Required') ? 'has-error' : ''; ?>" style="padding: 10px;overflow: hidden;">
                                    <label>Department</label>
                                    <select name="department" class="form-control" id="department">
                                      <option value="IT" <?php if($department=="IT") echo "selected";?> >IT</option>
                                      <option value="CS" <?php if($department=="CS") echo "selected";?> >CS</option>
                                      <option value="MECH" <?php if($department=="MECH") echo "selected";?> >MECH</option>
                                      <option value="PIE" <?php if($department=="PIE") echo "selected";?> >PIE</option>
                                      <option value="EE" <?php if($department=="EE") echo "selected";?> >EE</option>
                                      <option value="ECE" <?php if($department=="ECE") echo "selected";?> >ECE</option>
                                    </select>
                                    <span class="help-block"><?php echo $department_err;?></span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 <?php echo (!empty($semester_err) && $semester_err!='Required') ? 'has-error' : ''; ?>" style="padding: 10px;overflow: hidden;">
                                    <label>Semester</label>
                                    <select name="semester" class="form-control" id="semester">
                                      <option value="1" <?php if($semester=="1") echo "selected";?>>1</option>
                                      <option value="2" <?php if($semester=="2") echo "selected";?>>2</option>
                                      <option value="3" <?php if($semester=="3") echo "selected";?>>3</option>
                                      <option value="4" <?php if($semester=="4") echo "selected";?>>4</option>
                                      <option value="5" <?php if($semester=="5") echo "selected";?>>5</option>
                                      <option value="6" <?php if($semester=="6") echo "selected";?>>6</option>
                                      <option value="7" <?php if($semester=="7") echo "selected";?>>7</option>
                                      <option value="8" <?php if($semester=="8") echo "selected";?>>8</option>
                                    </select>
                                    <span class="help-block"><?php echo $semester_err;?></span>
                                </div>
                                <div class="col-md-4 <?php echo (!empty($subject_err) && $subject_err!='Required') ? 'has-error' : ''; ?>" style="padding: 10px;overflow: hidden;">
                                    <label>Subject</label>
                                    <select name="subject" class="form-control"  id="sub_code">
                                    </select>
                                    <span class="help-block"><?php echo $subject_err;?></span>
                                </div>
                                <div class="col-md-4 <?php echo (!empty($sections_err) && $sections_err!='Please Select a Section') ? 'has-error' : ''; ?>" style="padding: 10px;overflow: hidden;">
                                    <label>Section</label>
                                    <select name="sections[]" class="form-control" multiple id="sec_code">
                                    </select>
                                    <span class="help-block"><?php echo $sections_err;?></span>
                                </div>
                            </div>
                        </div>  
                        <hr>
                        <button type="button" class="btn btn-success" data-toggle="collapse" data-target="#f_ip_2">Time Table Via xlsx</button>    
                        <div class="form-row collapse" id="f_ip_2">
                            <div class="col-md-4 col-md-offset-8 <?php echo (!empty($xl_file_err) && $xl_file_err!='If you uploaded a file here, then all other fields will be ignored') ? 'has-error' : ''; ?>" style="padding: 10px;overflow: hidden;">
                                <label>Excel Format</label>
                                <input type="file" name="st_data" value="Input xlsx file" class="form-control" style="max-width: 80%;" />
                                <span class="help-block"><?php echo $xl_file_err;?></span>
                            </div>
                        </div>
                        <hr>
                        <div  class="form-row" >
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-info" value="Submit">
                                <a href="index.php" class="btn btn-default">Cancel</a>
                                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#time_table">Preview</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>        
        </div>
    </div>
</div>




    <div class="wrapper">
        <div class="container">
            <div class="row collapse" id="time_table">
                <div class="col-md-12" >
                    <?php
                    // Include config file
                    require_once "../DB/config.php";
                    
                    if($department_ == "*")
                        $sql = "SELECT name,time_table FROM teacher_master WHERE user_id='$userId_'";
                    else
                        $sql ="SELECT name,time_table FROM teacher_master WHERE department='$department_' and user_id='$userId_'";
                    $table_name = NULL;
                    $user_name = NULL;
                    if($result = $connection->query($sql)){
                        if($result->num_rows > 0){
                            $row = $result->fetch_assoc();
                            $table_name = $row['time_table'];
                            $user_name = $row['name'];
                            $result->free();
                        } else{
                            die("No records were found.");
                        }
                    } else{
                        // die("ERROR: " . $connection->error);
                        echo "ERROR: Could not able to execute";
                    }?>

                    <div class="page-header clearfix">
                        <h3 class="pull-left"><?=$user_name?></h3>
                    </div>
                    
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM ".$table_name;
                    $counter_index = 1;
                    $schedule = array();
                    for($i=0;$i<7;$i++){
                        array_push($schedule,NULL);
                        $schedule[$i] = array();
                        for($j=0;$j<5;$j++){
                            array_push($schedule[$i], NULL);
                        }
                    }
                    // $schedule[7][5] = 0;
                    // [period][day]

                    if($result = $connection->query($sql)){
                        if($result->num_rows > 0){
                            while($row = $result->fetch_array()){
                                $dy = $row['day'];
                                $pr = $row['period'];
                                $sm = $row['semester'];
                                $sb = $row['subject'];
                                $sc = $row['section'];
                                $dp = $row['department'];   
                                $schedule[$pr-1][$dy-1] = "Subject:- ".$sb."<br>Sections:- ".$sc."<br>Department:- ".$dp."<br>Semester:- ".$sm;
                            }
                            // Free result set
                            $result->free();
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute";
                    }
                    
                    // Close connection
                    $connection->close();
                    echo '<div class="table-responsive">';
                    echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>Mon</th>";
                                echo "<th>Tue</th>";
                                echo "<th>Wed</th>";
                                echo "<th>Thu</th>";
                                echo "<th>Fri</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                            for($i=0;$i<7;$i++){
                                echo "<tr>";
                                    echo "<td>" . $counter_index++ . "</td>";
                                    for($j=0;$j<5;$j++){
                                        if($schedule[$i][$j])
                                            echo "<td >". $schedule[$i][$j] ."</td>";
                                        else
                                            echo "<td>-</td>";

                                    }
                                echo "</tr>";
                            }
                        echo "</tbody>";                            
                    echo "</table>";
                    echo "</div>";
                    ?>
                </div>
            </div>        
        </div>
    </div>

    
</body>
<script type="text/javascript">
    function get_code() {
            var dept = $('#department').val();
            var sem = $('#semester').val();
            // console.log(dept);
            // console.log(sem);

            $.ajax({
                type: "POST",
                data: {dept: dept,sem: sem},
                url: '../config/get_subject.php',
                success: function(data) {
                    if(data==""){
                        var el = $("#sub_code");
                        el.empty(); // remove old options
                    }else{
                        data = JSON.parse(data);
                        // console.log(data);
                        var el = $("#sub_code");
                        el.empty(); // remove old options
                        $.each(data, function(key, value) {
                            el.append("<option value='"+value+"'>"+value+"</option>");
                        });   
                    }                                                  
                }
            });
            // console.log($(this).val());
            $.ajax({
                type: "POST",
                data: {dept: dept,sem: sem},
                url: '../config/get_section.php',
                success: function(data) {
                    if(data==""){
                        var el = $("#sec_code");
                        el.empty(); // remove old options
                    }else{
                        data = JSON.parse(data);
                        // console.log(data);
                        var el = $("#sec_code");
                        el.empty(); // remove old options
                        $.each(data, function(key, value) {
                            el.append("<option value='"+value+"'>"+value+"</option>");
                        });   
                    }                                                  
                }
            }); 
    }
    $(document).on("change", '#department', get_code);
    $(document).on("change", '#semester', get_code);

    window.onload = get_code();
</script>
</html>