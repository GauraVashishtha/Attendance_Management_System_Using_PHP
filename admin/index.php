<?php
    session_start();
    if(isset($_SESSION['department']))
        $department_ = $_SESSION['department'];
    else
        header("Location: ../index.php");
    $_SESSION['message'] = "Loged out successfully";

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <?php 
            echo '<li><a href="./add_std.php">Add Student</a> </li>';
            echo '<li><a href="./add_emp.php">Add Teacher</a> </li>';
            if($department_ == "*"){
                echo '<li><a href="./add_admin.php">Add Admin</a> </li>';
                echo '<li><a href="./add_config.php">Add Scheme</a> </li>';
            }
            echo '<li><a href="./gen_report.php">Get Report</a></li>';
        ?>
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
<?php 
    // Include config file
    require_once "../DB/config.php";
?>

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <!-- <h2 class="pull-left">Teachers Details</h2> -->

                        <div class="pull-right">
                        </div>

                          <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#teacher_showcase">Teachers </a></li>
                            <li><a data-toggle="tab" href="#student_showcase">Students</a></li>
                            <li><a data-toggle="tab" href="#config_showcase">Scheme</a></li>
                            <?php 
                                if($department_ == "*"){
                                    echo '<li><a data-toggle="tab" href="#admin_showcase">Admins</a></li>';
                                }
                            ?>
                          </ul>

                    </div>

                    <div class="tab-content">
                        
                        <div id="teacher_showcase" class="tab-pane fade in active table-responsive">
                            <?php
                            
                                // Attempt select query execution
                                if($department_ == "*")
                                    $sql = "SELECT * FROM teacher_master";
                                else
                                    $sql = "SELECT * FROM teacher_master WHERE department='$department_'";

                                if($result = $connection->query($sql)){
                                    if($result->num_rows > 0){
                                        $curr_idx = 1;
                                        echo "<table class='table table-bordered table-striped'>";
                                            echo "<thead>";
                                                echo "<tr>";
                                                    echo "<th>#</th>";
                                                    echo "<th>Name</th>";
                                                    echo "<th>Department</th>";
                                                    echo "<th>user_id</th>";
                                                    echo "<th>Time Table</th>";
                                                echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            while($row = $result->fetch_array()){
                                                echo "<tr>";
                                                    echo "<td>" . $curr_idx++ . "</td>";
                                                    echo "<td>" . $row['name'] . "</td>";
                                                    echo "<td>" . $row['department'] . "</td>";
                                                    echo "<td>" . $row['user_id'] . "</td>";
                                                    echo "<td> <form action='./load_tt.php' method='POST'>";
                                                    echo "
                                                        <input type='hidden' name='userId' value='".$row["user_id"]."'/>
                                                        <button type='submit' name='submit' title='Time Table Entry' data-toggle='tooltip' class='glyphicon glyphicon-th btn btn-default'></button>
                                                    ";
                                                    echo "</form> </td>";
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
                            
                            ?>
                        </div>

                        <?php 

                            // if($department_=="*"){
                                echo '<div id="student_showcase" class="tab-pane fade table-responsive">';

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
                                                if($department_!="*")
                                                    $sql1 = "SELECT DISTINCT class from $t_n where class LIKE '$department_%'";
                                                
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
                                }

                                echo '</div>';
                            // }
                        ?>

                        <?php 

                            if($department_=="*"){
                                echo '<div id="admin_showcase" class="tab-pane fade table-responsive">';

                                // Attempt select query execution
                                $sql = "SELECT * FROM admin_master";

                                if($result = $connection->query($sql)){
                                    if($result->num_rows > 0){
                                        $curr_idx = 1;
                                        echo "<table class='table table-bordered table-striped'>";
                                            echo "<thead>";
                                                echo "<tr>";
                                                    echo "<th>#</th>";
                                                    echo "<th>User Id</th>";
                                                    echo "<th>Password</th>";
                                                    echo "<th>Department</th>";
                                                echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            while($row = $result->fetch_array()){
                                                echo "<tr>";
                                                    echo "<td>" . $curr_idx++ . "</td>";
                                                    echo "<td>". $row['user_id'] ."</td>";
                                                    echo "<td>". $row['pass'] ."</td>";
                                                    echo "<td>". $row['department'] ."</td>";
                                                echo "</tr>";
                                            }
                                            echo "</tbody>";                            
                                        echo "</table>";
                                        // Free result set
                                        $result->free();
                                    } else{
                                        echo "<p class='lead'><em>No records were found.</em></p>";
                                    }
                                }

                                echo '</div>';
                            }
                        ?>
                        <?php 

                                echo '<div id="config_showcase" class="tab-pane fade table-responsive">';

                                // Attempt select query execution
                                $sql = "SELECT * FROM configuration";
                                if($department_!="*")
                                    $sql = "SELECT * FROM configuration WHERE parameter LIKE '$department_%'";

                                if($result = $connection->query($sql)){
                                    if($result->num_rows > 0){
                                        $curr_idx = 1;
                                        echo "<table class='table table-bordered table-striped'>";
                                            echo "<thead>";
                                                echo "<tr>";
                                                    echo "<th>#</th>";
                                                    echo "<th>Parameter</th>";
                                                    echo "<th>Value</th>";
                                                echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            while($row = $result->fetch_array()){
                                                echo "<tr>";
                                                    echo "<td>" . $curr_idx++ . "</td>";
                                                    echo "<td>". str_replace("_", " ", $row['parameter']) ."</td>";
                                                    echo "<td>". str_replace("_", ", ", $row['value']) ."</td>";
                                                echo "</tr>";
                                            }
                                            echo "</tbody>";                            
                                        echo "</table>";
                                        // Free result set
                                        $result->free();
                                    } else{
                                        echo "<p class='lead'><em>No records were found.</em></p>";
                                    }
                                }

                                echo '</div>';
                        ?>
                    </div>

                </div>
            </div>        
        </div>
    </div>

<?php 
    // Close connection
    $connection->close();
?>
</body>
</html>