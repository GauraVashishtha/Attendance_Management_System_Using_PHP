<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

  $roll_no_err = " ";
  $roll_no = "";
  $password = "";
  $password_err = " ";
  $email_err = " ";
  $email = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["roll_number"])) {
      $roll_no_err = "*Roll  Number is required";
    } else {
      $roll_no = test_input($_POST["roll_number"]);
      if (!preg_match('/^[1-9][0-9]*$/',$roll_no)) {
        $roll_no_err = "*Invalid Roll";
      }else{
        $roll_no_err = "";
      }
    }

    if (empty($_POST["password"])) {
      $password_err = "*Password is required";
    } else {
      $password = test_input($_POST["password"]);
      if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@_#$%]{8,12}$/',$password)){
        $password_err = "*Invalid Pass";
      }else{
        $password_err = "";
      }
    }
    
    if (empty($_POST["email"])) {
      $email_err = "*Password is required";
    } else {
      $email = test_input($_POST["email"]);
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $email_err = "*Invalid E-mail ";
      }else{
        $email_err = "";
      }
    }


  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
   return $data;
  }

  if($roll_no_err == "" && $password_err == "" && $email_err == ""){
    require_once  './Library/PHPMailer/src/PHPMailer.php';
    require_once  './Library/PHPMailer/src/Exception.php';
    require_once  './Library/PHPMailer/src/SMTP.php';
    require_once  './Library/PHPMailer/src/POP3.php';
    require_once  './Library/PHPMailer/src/OAuth.php';





    /* Create a new PHPMailer object. Passing TRUE to the constructor enables exceptions. */
    $mail = new PHPMailer(TRUE);
    try {
       $mail->setFrom('cnischay007@gmail.com', 'Campify12');
       $mail->addAddress($email, 'Emperor');
       
       $mail->Subject = 'Verify E-mail';

       $data = md5($roll_no).md5($password).md5($email);
        
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $file_name = explode("/", $url);
        $file_name = $file_name[count($file_name)-1];
        $url = str_replace($file_name, "", $url);

       $data = $url.'verify.php?h='.$data;

       $mail->Body = 'Please Click link below to verify your e-mail '.$data;
      
        /* Use SMTP. */
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
       $mail->Username = 'cnischay007@gmail.com';
       $mail->Password = 'pcuzketzsgnqznpa';
       
       /* Enable SMTP debug output. */
       // $mail->SMTPDebug = 4;
       
       $mail->send();

       $sql = "INSERT INTO `student_user`(`roll_number`, `pass`, `e_mail`, `verified`) VALUES ('$roll_no','$password','$email','0') ON DUPLICATE KEY UPDATE pass='$password', e_mail='$email', verified='0'";
       // echo $sql;

        require_once  './DB/config.php';
       $res = $connection->query($sql);
       $connection->close();
       if($res){
          session_start();
          $_SESSION['message'] = "Registered Successfully, go to your mail for verification";
          header('Location: ./index.php');
       }
    }
    catch (Exception $e)
    {
       // echo $e->errorMessage();
    }
    catch (\Exception $e)
    {
       // echo $e->getMessage();
    }
    session_start();
    if(!isset($_SESSION['message'])){
      $_SESSION['message'] = "Unable to connect to server";
    }
    header('Location: ./index.php');

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
                    <div class="page-header">
                        <h2>Register</h2>
                    </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post"  enctype="multipart/form-data">
                            <div class="form-group">
                                <input style="color: black;" type="text" name="roll_number" class="form-control" placeholder="Roll Number" value="<?php if($roll_no_err=="") echo $roll_no;?>"/>
                                <span class="help-block" style="color: white;"><?php if($roll_no_err) echo $roll_no_err;?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" placeholder="E-mail"  value="<?php if($email_err=="") echo $email;?>"/>
                                <span class="help-block" style="color: white;"><?php if($email_err) echo $email_err;?></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Password"  value="<?php if($password_err=="") echo $password;?>" />
                                <span class="help-block" style="color: white;"><?php if($password_err) echo $password_err;?></span>
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
