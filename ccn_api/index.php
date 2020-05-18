<?php 


if(isset($_POST['roll_number'])){
        session_start();
        session_unset();
        $_SESSION['roll_num'] = $_POST['roll_number'];
        header("Location: ../student/index.php");
}else{
    header("Location: ../index.php");
}

?>