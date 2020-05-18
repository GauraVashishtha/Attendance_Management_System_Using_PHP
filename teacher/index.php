<?php 
  session_start();
  if (isset($_SESSION['time_table']) && isset($_SESSION['log_table']) && isset($_SESSION['name'])  && isset($_SESSION['user_id']) ){
      $time_table_ = $_SESSION['time_table'];
      $log_table_ = $_SESSION['log_table'];
      $name_ = $_SESSION['name'];
      $user_id_ = $_SESSION['user_id'];
  }else{
    header('Location: ../index.php');
  }


?>

<!-- 
<div class="container text-center">    
  <div class="row content">
    <div class="col-sm-12 text-left"> 
      <h3 class="text-right"><?=$name_;?></h3>
      <hr>
      
      <a href="./mark_att.php" class="btn btn-default" role="button">Mark Attendance</a>
      <a href="#" class="btn btn-default" role="button">Generate Report</a>

    </div>
  </div>
</div> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
        <li><a href="../index.php"><span class="glyphicon glyphicon-log-in"></span> LogOut</a></li>
        <!-- <li><a href="./tt_loader/register.php"> Register </a></li> -->
      </ul>
    </div>
  </div>
</nav>
  


<?php 
   if(isset($_SESSION['mark_att_message']) && !empty($_SESSION['mark_att_message']) ){
      echo $_SESSION['mark_att_message'];
      $_SESSION['mark_att_message'] = "";
   }  


?>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left"> Roll Sheets</h2>
                        <!-- <a href="create.php" class="btn btn-success">Add New Employee</a> -->
                        <div class="pull-right">
                          <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#time_table">TimeTable</button>
                          <a href="./mark_att.php" class="btn btn-success" role="button">Mark Attendance</a>
                        </div>
                        <!-- <a href="#" class="btn btn-default" role="button">Generate Report</a> -->
                    </div>


                    <div class="wrapper">
                        <div class="container">
                            <div class="row collapse" id="time_table">
                                <div class="col-md-12">
                                    <?php
                                    // Include config file
                                    require_once "../DB/config.php";
                                    
                                    // Attempt select query execution
                                    $sql = "SELECT * FROM ".$time_table_;
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
                                        echo "ERROR: Could not able to execute $sql. " . $connection->error;
                                    }
                                    
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
                                    echo '</div>';
                                    ?>
                                </div>
                            </div>        
                        </div>
                    </div>



                    <div>
                      <?php
                      // Include config file
                      require_once "../DB/config.php";
                      
                      // Attempt select query execution
                      $sql = "SELECT * FROM $log_table_";
                      $curr_idx = 1;
                      if($result = $connection->query($sql)){
                          if($result->num_rows > 0){

                              echo '<div class="table-responsive">';
                              echo "<table class='table table-bordered table-striped'>";
                                  echo "<thead>";
                                      echo "<tr>";
                                          echo "<th>#</th>";
                                          echo "<th>Subject</th>";
                                          echo "<th>Department</th>";
                                          echo "<th>Semester</th>";
                                          echo "<th>Section</th>";
                                          echo "<th>Action</th>";
                                      echo "</tr>";
                                  echo "</thead>";
                                  echo "<tbody>";
                                  while($row = $result->fetch_array()){
                                      echo "<tr>";
                                          echo "<td>" . $curr_idx++ . "</td>";
                                          $tt_string = explode("_", $row['sheet_table']);
                                          echo "<td>$tt_string[1]</td>";
                                          echo "<td>$tt_string[2]</td>";
                                          echo "<td>$tt_string[3]</td>";
                                          echo "<td>$tt_string[4]</td>";
                                          echo "<td>";
                                              echo "<a href='read_att.php?id=". $row['sheet_table'] ."' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                              echo "<a href='mark_att.php?subject=$tt_string[1]&department=$tt_string[2]&semester=$tt_string[3]&section=$tt_string[4]' title='Add New Record' data-toggle='tooltip'><span class='glyphicon glyphicon-edit'></span></a>";
                                          echo "</td>";
                                      echo "</tr>";
                                  }
                                  echo "</tbody>";                            
                              echo "</table>";
                              echo '</div>';
                              // Free result set
                              $result->free();
                          } else{
                              echo "<p class='lead'><em>No records were found.</em></p>";
                          }
                      } else{
                          echo "ERROR: Could not able to execute ";
                      }
                      
                      // Close connection
                      $connection->close();
                      ?>
                    </div>
                </div>
            </div>        
        </div>
    </div>

<footer class="container-fluid text-center">
  <!-- <p>Footer Text</p> -->
</footer>

</body>
<script type="text/javascript">
    
 </script>
</html>
