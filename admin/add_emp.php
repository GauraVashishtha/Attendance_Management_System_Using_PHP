<?php 
	session_start();
	if(isset($_SESSION['department']))
		$department_ = $_SESSION['department'];
	else
        header("Location: ../index.php");
?>


<?php
  // define variables and set to empty values
  $name_err = "Required Field";
  $name = "";
  $userID_err = "Required Field";
  $userID = "";
  $password_err = "Required Field";
  $password = "";
  $department_err = "Required Field";
  $department = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
      $name_err = "Name is required";
    } else {
      $name = test_input($_POST["name"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $name_err = "Only letters and white space allowed";
      }else{
        $name_err = "";
      }
    }

    if (empty($_POST["userID"])) {
      $userID_err = "*Name is required";
    } else {
      $userID = test_input($_POST["userID"]);
      if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@_#$%]{8,12}$/',$userID)) {
        $userID_err = "* Must contain at least 1 number and 1 letter, Must be 8-12 characters, And only @_#$% are allowed";
      }else{
        require_once '../DB/config.php';
        $sql = "SELECT * FROM teacher_master WHERE user_id='".$userID."'";
        $res = $connection->query($sql);

        if($res->num_rows>0){
          $userID_err = "User Id exist, choose another one.";
        }else{
          $userID_err = "";
        }
        // $connection->close();
      }
    }

    if (empty($_POST["password"])) {
      $password_err = "*Password is required";
    } else {
      $password = test_input($_POST["password"]);
      if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@_#$%]{8,12}$/',$password)){
        $password_err = "* Must contain at least 1 number and 1 letter, Must be 8-12 characters, And only @_#$% are allowed";
      }else{
        $password_err = "";
      }
    }

    if(empty($_POST['department'])){
    	$department_err = "Please Selct a department";
    }else{
    	$department = test_input($_POST['department']);
    	$department_err = "";
    }
  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $errors = "";

  if($name_err=="" && $userID_err=="" && $password_err=="" && $department_err==""){
  	// echo $name,$userID,$password,$department;
  	$time_table = $userID."_".$department."_".date('m')."_".date('Y');
  	$log_table = $time_table."_log";
  	require_once '../DB/config.php';
  	$sql = "INSERT INTO teacher_master 
  			VALUES ('$name','$userID','$password','$department','$time_table','$log_table')";
  	// echo "<hr>".$sql;
  	if(!$connection->query($sql)){$errors = "Error making a teacher entry";}

	  $sql = "CREATE TABLE IF NOT EXISTS ".$time_table."
             (
             	day varchar(100) NOT NULL,
             	period varchar(100) NOT NULL,
              semester varchar(100) NOT NULL,
              subject varchar(100) NOT NULL,
              department varchar(100) NOT NULL,
             	section varchar(100) NOT NULL,
             	CONSTRAINT PK_".$time_table." PRIMARY KEY(day,period)
             );";
  	if(!$connection->query($sql)){$errors = "Error making space for time table";}
    $sql = "CREATE TABLE IF NOT EXISTS ".$log_table."
             (
             	sheet_table varchar(100) NOT NULL,
             	PRIMARY KEY(sheet_table)
             );";
  	if(!$connection->query($sql)){$errors = "Error making space for log table";}

    // echo "HII:: ";
    if($errors=="")
      $errors="New Techer added successfully";

    $_SESSION['popUp'] = $errors;
    // echo $errors;
    // echo $_SESSION['popUp'];
    $connection->close();
  	header("Location: ./index.php");
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
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Add New Teacher</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="inner_form">
                      <div class="form-row">
                        <div class="col-md-6">
                          <div class="form-group <?php echo (!empty($name_err) && $name_err!='Required Field') ? 'has-error' : ''; ?>">
                              <label>Name</label>
                              <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                              <span class="help-block"><?php echo $name_err;?></span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group <?php echo (!empty($userID_err) && $userID_err!='Required Field') ? 'has-error' : ''; ?>">
                              <label>userID</label>
                              <input type="text" name="userID" class="form-control" value="<?php echo $userID; ?>">
                              <span class="help-block"><?php echo $userID_err;?></span>
                          </div>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-6">
                          <div class="form-group <?php echo (!empty($password_err) && $password_err!='Required Field') ? 'has-error' : ''; ?>">
                              <label>password</label>
                              <input type="text" name="password" class="form-control" value="<?php echo $password; ?>">
                              <span class="help-block"><?php echo $password_err;?></span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group <?php echo (!empty($department_err) && $department_err!='Required Field') ? 'has-error' : ''; ?>">
                              <label>department</label>
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
                                  ?>
                              </select>
                              <span class="help-block"><?php echo $department_err;?></span>
                          </div>
                        </div>
                      </div>

                        <input type="submit" class="btn btn-info" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>



</body>
</html>