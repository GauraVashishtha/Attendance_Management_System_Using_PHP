<?php
    session_start();
    if(isset($_SESSION['department']) && $_SESSION['department']=="*")
        $department = $_SESSION['department'];
    else
        header("Location: ../index.php");
    $_SESSION['message'] = "Loged out successfully";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
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

    if(isset($_SESSION['popUp']) && $_SESSION['popUp']!=""){
        echo '<div class="container">
                    <div class="alert alert-warning">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        '.$_SESSION['popUp'].'
                    </div>
                </div>';
        unset($_SESSION['popUp']);
    }

?>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Student Details</h2>
                        <div class="pull-right">
                            <a href="./index.php" class="btn btn-default">Back</a>
                            <a href="./add_std.php" class="btn btn-success">Add Student</a>
                        </div>
                    </div>
                    <?php
                    // Include config file
                    require_once "../DB/config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM student_master";

                    if($result = $connection->query($sql)){
                        if($result->num_rows > 0){
                            $curr_idx = 1;
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Year</th>";
                                        echo "<th>Classes</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch_array()){
                                    $t_n = $row['table_name'];
                                    $sql1 = "SELECT DISTINCT class from $t_n";
                                    $res1 = $connection->query($sql1);
                                    if(!$res1){ die("connection Error: ".$connection->error);}
                                    $class_list = array();
                                    while($row1 = $res1->fetch_assoc())
                                        array_push($class_list, $row1['class']);
                                    sort($class_list);
                                    echo "<tr>";
                                        echo "<td>" . $curr_idx++ . "</td>";
                                        echo "<td>" . $row['year'] . "</td>";
                                        echo "<td>";
                                        foreach ($class_list as $cl) {
                                            echo $cl." ";
                                        }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . $connection->error;
                    }
                    
                    // Close connection
                    $connection->close();
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>