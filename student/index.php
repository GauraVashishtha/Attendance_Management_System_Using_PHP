<?php 
  session_start();
  if(isset($_SESSION['roll_num'])){
    $roll_num_ = $_SESSION['roll_num'];
  }else{
    header("Location: ../index.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assests/css/lib3.min.css">
    <script type="text/javascript" src="../assests/js/jlib.min.js"></script>
    <script type="text/javascript" src="../assests/js/lib3.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../assests/css/main.css">
</head>
<body>

<nav class="navbar">
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
        <li><a href="../index.php"><span class="glyphicon glyphicon-log-out"></span> LogOut</a></li>
        <!-- <li><a href="./tt_loader/register.php"> Register </a></li> -->
      </ul>
    </div>
  </div>
</nav>



  <div class="container" style="margin-top: 40px;margin-bottom: 40px;">
    <div class="row">    
      <div class="col-md-1">
        
      </div>
      <div class="col-md-10 " id="att_shower">
        <!-- <table class='table table-bordered table-striped' id='roll_sheet_table'>
          <tr>
            <th>Subject</th>
            <th>Attendance</th>
            <th>Percetange</th>
            <th>View Log</th>
          </tr>
        </table> -->
      </div>
    </div>
  </div>


<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>

</body>
<script type="text/javascript">
  

    var el = $("#att_shower");
    // var el = document.getElementById('att_shower');
      $.ajax({
          type: "POST",
          data: {roll_num:"<?=$roll_num_?>"},
          url: './api_get_st_att.php',
          success: function(data) {
              // console.log(data);    

              if(data==""){
                  // el.append("No Records were found.");
                  el.empty(); // remove old options
              }else{
                  data = JSON.parse(data);
                  // console.log(data);
                  el.empty(); // remove old options
                  var to_add = "<h4>Name:- "+data[0]+"</h4>";
                  // var to_names = "";
                  to_add += ("<table class='table table-bordered table-striped'>");
                  // console.log(data);
                  // console.log(Object.keys(data).length);

                    to_add += '<tr> <th>Subject</th> <th>Attendance</th> <th>Total</th> <th>Percentage</th> <th>Complete Logs</th> </tr>';

                    for(var i=1;i<Object.keys(data).length;i++){
                      to_add += ("<tr>");
                      // to_names += "<option value='";
                      // to_names += data[i][0];
                      // to_names += "'>";
                      to_add += "<td>"+data[i][0].split("_")[1]+"</td>";
                      to_add += "<td>"+data[i][1]+"</td>";
                      to_add += "<td>"+data[i][2]+"</td>";
                      to_add += "<td>"+Math.round((parseInt(data[i][1])/parseInt(data[i][2]))*1000)/10+" %</td>";
                      to_add += '<td><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#att_data'+i+'">Get Log</button>';
                      to_add += `<div id="att_data`+i+`" class="collapse" >
                                    <table class='table table-bordered table-striped' style="display: block;height: 200px; overflow-y:scroll; overflow-x:hidden;">
                                      <tr>
                                        <th>Date</th>
                                        <th>Period</th>
                                        <th>Status</th>
                                      </tr>`;

                      for(var j=0;j<Object.keys(data[i][3]).length;j++){
                        to_add += `<tr> <td>`+data[i][3][j][0]+`</td> <td>`+data[i][3][j][1]+`</td> <td>`+data[i][3][j][2]+`</td> </tr>`;
                      }
                    


                          to_add += `</table>
                                  </div>`;

                      to_add +='</td>';
                      to_add += ("</tr>");


                    }
                  to_add += ("</table>");

                  // console.log("Hii "+to_add);

                  el.append(to_add);
              }                                                  
          }
      });    
 </script>
</html>
