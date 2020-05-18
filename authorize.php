
<?php
  session_start();
  session_unset();
  session_destroy();
  session_start();
  // define variables and set to empty values
  $nameErr = $passErr = "*Required Field";
  $name = $pass = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
      $nameErr = "*Name is required";
    } else {
      $name = test_input($_POST["name"]);
      $nameErr = "";
    }
    if (empty($_POST["pass"])) {
      $passErr = "*Password is required";
    } else {
      $pass = test_input($_POST["pass"]);
      $passErr = "";
    }
  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if($nameErr=="" && $passErr==""){

    require './DB/config.php';

    $sql = "SELECT * from student_user WHERE roll_number='$name' and pass='$pass'";
    $res = $connection->query($sql);
    if($res->num_rows>0){
      $row = $res->fetch_assoc();
      if($row['verified']==0){
        $_SESSION['message'] = "Please verify your e-mail, via link mailed to you";
        header("Location: ./index.php");
      }else{
        $_SESSION['roll_num'] = $name;
        header("Location: ./student/index.php");
      }
    }

    $sql = "SELECT * from admin_master WHERE user_id='".$name."' and pass='".$pass."'";
    $res = $connection->query($sql);
    if($res->num_rows>0){
      $res = $res->fetch_assoc();
      $_SESSION["department"] = $res['department'];
      $connection->close();
      header("Location: ./admin/index.php");
    }

    $sql = "SELECT * from teacher_master WHERE user_id='".$name."' and pass='".$pass."'";
    $res = $connection->query($sql);

    if($res->num_rows>0){
        $row = $res->fetch_assoc();
        $_SESSION['time_table'] = $row['time_table'];
        $_SESSION['log_table'] = $row['log_table'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
  
        $connection->close();    
        header("Location: ./teacher/index.php");
    }

    $connection->close();


    // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    // header("Location: ".$actual_link);
    $passErr = "Invalid Username or password";
    $name = "";
    $pass = "";



  }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./assests/css/lib3.min.css">
    <script type="text/javascript" src="./assests/js/jlib.min.js"></script>
    <script type="text/javascript" src="./assests/js/lib3.min.js"></script>
  <link rel="stylesheet" type="text/css" href="./assests/css/main.css">
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
        <!-- <li><a href="./authorize.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li> -->
      </ul>
    </div>
  </div>
</nav>

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="authorize_form col-md-6 col-md-offset-3 ">
                    <div class="page-header" style="margin-top: 0px;">
                        <h2>LogIn</h2>
                    </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post"  enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="UserName" value="<?php if($nameErr=="") echo $name;?>" />
                                <span class="help-block" style="color: white;"><?php if($nameErr) echo $nameErr;?></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="pass" class="form-control" placeholder="Password"  value="<?php if($passErr=="") echo $pass;?>" />
                                <span class="help-block" style="color: white;"><?php if($passErr) echo $passErr;?></span>
                            </div>
                            <div class="form-group">
                              <input type="submit" class="btn btn-default" value="Submit">
                              <a href="./index.php" class="btn btn-default pull-right">Cancel</a>
                            </div>
                        </form>
                </div>
            </div>        
        </div>
    </div>

</body>
</html>
