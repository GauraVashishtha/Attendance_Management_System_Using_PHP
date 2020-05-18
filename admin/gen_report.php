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
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assests/css/lib3.min.css">
    <script type="text/javascript" src="../assests/js/jlib.min.js"></script>
    <script type="text/javascript" src="../assests/js/lib3.min.js"></script>
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

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h3 class="pull-left">
                             Report Generation             
                        </h3>
                        <div class=" pull-right">
                          <a href="./index.php" class="btn btn-default" role="button">Back</a>

                          <span class="col" id="downloadLink" style="margin-left: 5px;">
                            <!-- <a href="./index.php" class="btn btn-default" role="button">Back1</a> -->
                          </span>
                        </div>
                      <!-- <a href="#" class="btn btn-default" role="button">Generate Report</a> -->
                    </div>
                </div>
            </div>        
        </div>
    </div>

                     
    <div class="container  inner_form">
      <div class="row">    
        <div class="col-sm-4 col-sm-offset-2">
<!--           Percentage:<span id="filter_per_show"> 100 %</span>
          <input  id='filter_per' oninput='filter_per()' title="Percentage below"  type="range" min="0" max="100" value="100"> -->
                                <select name="year" class="form-control" id="dept_req">
                                  <option value="">Department</option>
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
        <div class="col-sm-4">
                                <select name="year" class="form-control" id="year_req">
                                  <option value="">Year</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                </select>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-sm-10">
          <button class="btn btn-success pull-right" onclick="activate()">Generate report</button>
        </div>
        <div class="col-sm-2">  
        </div>
      </div>
    </div>


    <div class="container">
    <hr>
      <div class="row">    
        <div style="overflow-x: auto;white-space: nowrap;" class="table-responsive">
          <table class="table table-bordered table-striped" id="det_sheet">
            
          </table>
        </div>
      </div>
    </div>
<div id="buffer_data" style="display: none;"></div>
<footer class="container-fluid text-center">
  <!-- <p>Footer Text</p> -->
</footer>

</body>
<script type="text/javascript">
  function activate(){
    var dept = $("#dept_req").val();
    var year = $("#year_req").val();
    if(dept==""){
      alert("Please select a department");
      return;
    }
    if(year==""){
      alert("Please select an year");
      return;
    }
    get_code(dept,year);
  }




  function makeCSV() {
      var dept = $("#dept_req").val();
      var year = $("#year_req").val();
      var t_name = dept+"_"+year;
      var csv = "";

      $("#det_sheet").find("tr").each(function () {
          var sep = "";
          $(this).find("th").each(function () {
              csv += sep + $(this).text();
              sep = ",";
          });
          $(this).find("td").each(function () {
              csv += sep + $(this).text();
              sep = ",";
          });
          csv += "\n";
      });
      // console.log(csv);
      window.URL = window.URL || window.webkiURL;
      var blob = new Blob([csv]);
      var blobURL = window.URL.createObjectURL(blob);
      $("#downloadLink").html("");
      $("<a data-toggle='tooltip' class='btn btn-danger pull-right' title='Press to download data' role='button' > </a>").
      attr("href", blobURL).
      attr("download", t_name+".csv").
      text("Download").
      appendTo('#downloadLink');
  }

  // $('#loading-image').bind('ajaxStart', function(){
  //     $(this).show();
  // }).bind('ajaxStop', function(){
  //     $(this).hide();
  // });

    function get_code(dept,year) {
      $.ajax({
          type: "POST",
          data: {dept:dept,year:year},
          url: '../student/api_get_st_rl_nm.php',
          success: function(data) {
              if(data!=""){
                  data = JSON.parse(data);
                  // console.log(data);
                  $('#buffer_data').html(JSON.stringify(data, undefined, 2));
              }
          },
          complete: function(data) {
            rep_gen();
          } 
      }); 
    }

    function rep_gen(){
      roll_data = JSON.parse($('#buffer_data').html());
      // console.log(roll_data);

      var el = $('#det_sheet');
      el.empty();
      el.append("<tr> <th>Roll No.</th> <th>Name</th> <th>Department</th> <th>Section</th> <th>Subjects</th> </tr>");

      for(i=0;i<Object.keys(roll_data).length;i++){
        $.ajax({
          type: "POST",
          data: {roll_num:roll_data[i][1],det_lis:"1"},
          url: '../student/api_get_st_att.php',
          success: function(data) {
              if(data!=""){
                  data = JSON.parse(data);
                  if(Object.keys(data).length!=0){
                    // console.log(data);
                    var to_add = '';
                    to_add += "<tr>";
                    to_add += "<td>"+data[1]+"</td>";
                    to_add += "<td>"+data[0]+"</td>";
                    to_add += "<td>"+data[2]+"</td>";
                    to_add += "<td>"+data[3]+"</td>";
                    to_add += '<td>';
                    var i=4;
                    for(;i<Object.keys(data).length-1;i++){
                        to_add += data[i][0].split("_")[1]+": - ";
                        to_add += Math.round((parseInt(data[i][1])/parseInt(data[i][2]))*1000)/10+" % <br>";
                    }
                    to_add += data[i][0].split("_")[1]+": - ";
                    to_add += Math.round((parseInt(data[i][1])/parseInt(data[i][2]))*1000)/10+" % ";
                    to_add += "</td></tr>";

                    el.append(to_add);  
                  }

              }                                                  
          },
          complete: function(){
            el.append("</table>");
            makeCSV();
          }
        });    
      }
    }


 </script>
</html>
