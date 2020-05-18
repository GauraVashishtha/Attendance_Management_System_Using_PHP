<?php 
	session_start();
	if(isset($_SESSION['department']))
		$department_ = $_SESSION['department'];
	else
        header("Location: ../index.php");
?>


<?php
  // define variables and set to empty values
  $department = "";
  $semester = "";
  $subject = "";
  $dept = "";
  $subject_err = "Required Field <br>{ Either _ seperated subject-list or just a subject }";
  $dept_err = "Required Field <br>{ Either _ seperated department-list or just a department }";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["subject"])) {
      $subject_err = "Subject is required";
    } 
    if(isset($_POST['subject']) && !empty($_POST['subject'])){
        $department = test_input($_POST['department']);
        $semester = test_input($_POST['semester']);
        $subject = test_input($_POST['subject']);
        $subject = explode("_", $subject);

        $par = $department."_".$semester."_sub";
        $val = "";

        require_once '../DB/config.php';
        $sql = "SELECT * FROM Configuration WHERE parameter='$par'";
        $res = $connection->query($sql);
        if($res->num_rows>0){
          $res = $res->fetch_assoc();
          $val = $res['value'];
        }

        if($val!=""){
          $val .= "_";
        }

        $i=0;
        for (;$i<count($subject)-1;$i++) {
          if($subject[$i]!=""){
            $val .= $subject[$i]."_";
          }
        }    
        if($subject[$i]!=""){
          $val .= $subject[$i];  
        }    

        // echo $val;

        $sql = "INSERT INTO configuration (parameter, value) VALUES('$par','$val') ON DUPLICATE KEY UPDATE parameter='$par', value='$val'";
        // echo $sql;
        $res = $connection->query($sql);

        if($res){  
          $_SESSION['popUp'] = "Data inserted successfully";
        }else{
          $_SESSION['popUp'] = "Some error occured";
        }

        // print_r($_SESSION);
        header("Location: ./index.php");          
    }

    if (isset($_POST['dept']) && !empty($_POST["dept"])){
        $dept = test_input($_POST['dept']);
        $dept = explode("_", $dept);

        $par = "DEPT";
        $val = "";

        require_once '../DB/config.php';
        $sql = "SELECT * FROM Configuration WHERE parameter='$par'";
        $res = $connection->query($sql);
        if($res->num_rows>0){
          $res = $res->fetch_assoc();
          $val = $res['value'];
        }

        if($val!=""){
          $val .= "_";
        }

        $i=0;
        for (;$i<count($dept)-1;$i++) {
          if($dept[$i]!=""){
            $val .= $dept[$i]."_";
          }
        }    
        if($dept[$i]!=""){
          $val .= $dept[$i];  
        }    

        // echo $val;

        $sql = "INSERT INTO configuration (parameter, value) VALUES('$par','$val') ON DUPLICATE KEY UPDATE parameter='$par', value='$val'";
        // echo $sql;
        $res = $connection->query($sql);

        if($res){  
          $_SESSION['popUp'] = "Data inserted successfully";
        }else{
          $_SESSION['popUp'] = "Some error occured";
        }

        // print_r($_SESSION);
        header("Location: ./index.php");          
    }



  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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


        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h4  data-toggle="collapse" data-target="#form_1" onmouseover="$(this).css('cursor','pointer');">Add Subjects</h4>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"  id="form_1" class="collapse inner_form">
                      <div class="form-row">
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
                          <div class="form-group ">
                              <label>Semester</label>
                              <select name="semester" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                              </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group ">
                              <label>Subject</label>
                              <input type="text" name="subject" class="form-control" value="<?php if(isset($val)) echo $val; ?>">
                              <span class="help-block"><?php echo $subject_err;?></span>
                          </div>
                        </div>
                      </div>
                      
                        <input type="submit" class="btn btn-info" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="page-header">
                        <h4  data-toggle="collapse" data-target="#demo" onmouseover="$(this).css('cursor','pointer');">Add Department</h4>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"  id="demo" class="collapse inner_form">
                      <div class="form-row">
                        <div class="col-md-12">
                          <div class="form-group col-md-4" style="padding-left: 0px;">
                              <label>Department</label>
                              <input type="text" name="dept" class="form-control" value="<?php if(isset($dept)) echo $dept; ?>">
                              <span class="help-block"><?php echo $dept_err;?></span>
                          </div>
                        </div>
                      </div>
                      <div class="form-row">
                        <input type="submit" class="btn btn-info" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                      </div>
                    </form>
                </div>
            </div>        
        </div>






</body>
</html>