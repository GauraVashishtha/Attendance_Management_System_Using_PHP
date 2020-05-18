<?php 
  session_start();
  if (isset($_SESSION['time_table']) && isset($_SESSION['log_table']) && isset($_SESSION['name'])  && isset($_SESSION['user_id']) && isset($_GET['id']) ){
      $time_table_ = $_SESSION['time_table'];
      $log_table_ = $_SESSION['log_table'];
      $name_ = $_SESSION['name'];
      $user_id_ = $_SESSION['user_id'];
      $table_name_ = $_GET['id'];
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
      .container{
        margin-top: 10px;
        margin-bottom: 10px;
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

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h4 class="pull-left">
                          <?php 
                                        $tt_string = explode("_", $table_name_);
                                        echo "<u>Subject</u>:- <span style='font-size:15px;'>$tt_string[1]</span>, ";
                                        echo "<u>Department</u>:- <span style='font-size:15px;'>$tt_string[2]</span>, ";
                                        echo "<u>Semester</u>:- <span style='font-size:15px;'>$tt_string[3]</span>, ";
                                        echo "<u>Section</u>:- <span style='font-size:15px;'>$tt_string[4]</span>.";
                          ?>
                                          
                        </h4>
                        <div class=" pull-right">
                          <a href="./index.php" class="btn btn-default" role="button">Back</a>

                          <?php
                           echo "<a href='mark_att.php?subject=$tt_string[1]&department=$tt_string[2]&semester=$tt_string[3]&section=$tt_string[4]' title='Add New Record' data-toggle='tooltip' class='btn btn-success' aria-disabled='true' role='button'>Add Attendance</a>"; 
                          ?>
                        </div>
                      <!-- <a href="#" class="btn btn-default" role="button">Generate Report</a> -->
                    </div>
                </div>
            </div>        
        </div>
    </div>

                     
    <div class="container">
      <div class="row">    
        <div class="col-sm-4">
          Percentage:<span id="filter_per_show"> 100 %</span>
          <input  id='filter_per' oninput='filter_per()' title="Percentage below"  type="range" min="0" max="100" value="100">
        </div>
        <!-- <div class="col-sm-3">
          Name: <input list='filter_name_list' type='text' id='filter_name' onkeyup='filter_name()' placeholder='filter'>
          <datalist id="filter_name_list">
          </datalist>
        </div> -->
        <div class="col-sm-4">
          <!-- Roll No: <input type='text' id='filter_pe' onkeyup='filter_per()' placeholder='filter'> -->
        </div>
        <div class="col-sm-4">
          <span id="downloadLink"></span>
          <p class="btn btn-info pull-right" id='pdf_download' style="margin-right: 5px;">Filtered PDF ↧</p> 
        </div>
      </div>
    </div>


    <div class="container">
      <div class="row">    
        <div id="roll_sheet" style="overflow-x: auto;white-space: nowrap;">
        </div>
      </div>
      <div id="roll_sheet_data" style="display: none;"></div>
    </div>

<footer class="container-fluid text-center">
  <!-- <p>Footer Text</p> -->
</footer>

</body>

<script type="text/javascript" src="https://unpkg.com/jspdf@1.5.3/dist/jspdf.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/jspdf-autotable@3.2.10/dist/jspdf.plugin.autotable.js"></script>

<script type="text/javascript">
  function filter_per(){
    var per = $('#filter_per').val();
    $('#filter_per_show').html(per+' %');
    // console.log(per);
    filter_per_helper(per);
  }

  $('#pdf_download').click(function () {  
    // console.log("1");
    var t_name = '<?=$table_name_?>';            
    var x = t_name.split("_");
    var temp ="";
    temp = "Sub_"+x[1]+"_Dept_"+x[2]+"_Sem_"+x[3]+"_Sec_"+x[4];
    t_name = temp;

    // var doc = new jsPDF();
    // // It can parse html:  
    // var tbl = $('#roll_sheet_table').clone();
    // tbl.find('tr th:nth-child(1), tr td:nth-child(1)').remove();


    // // doc.autoTable({html: '#roll_sheet_table'});
    // // console.log(tbl);
    // doc.autoTable(tbl.get());
    
    // doc.save(t_name+'.pdf');
    
    var doc = new jsPDF('p', 'pt', 'a4');
    var cols = $('#roll_sheet_table thead tr th').length-1;
    console.log(cols);
    var tbl = $('#roll_sheet_table').clone();
    tbl.find('tfoot').remove();
    
    // tbl.find('tr th:nth-child(1), tr td:nth-child(1)').remove();

    for(var i=2;i<cols;i++){
      // console.log(i+1);
      tbl.find('tr th:nth-child(3), tr td:nth-child(3)').remove();
    }

    var res = doc.autoTableHtmlToJson(tbl.get(0));


    doc.autoTable(res.columns, res.data, {
      startY: 60,
      styles: {
        overflow: 'linebreak',
        fontSize: 8,
        cellWidth: 'wrap'
      }
    });

    doc.output('dataurlnewwindow');
  });


  function makeCSV() {
      var t_name = '<?=$table_name_?>';            
      var csv = "";
      var x = t_name.split("_");
      var temp ="";
      temp = "Sub_"+x[1]+"_Dept_"+x[2]+"_Sem_"+x[3]+"_Sec_"+x[4];
      t_name = temp;

      $("#roll_sheet_table").find("tr").each(function () {
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
      $("<a data-toggle='tooltip' class='btn btn-info pull-right' title='Press to download data' role='button' ></a> ").
      attr("href", blobURL).
      attr("download", t_name+".csv").
      text("Complete CSV ↧").
      appendTo('#downloadLink');
  }

  function get_code() {
    var t_name = '<?=$table_name_?>';            
    // console.log(t_name);

    var el = $("#roll_sheet");
      $.ajax({
          type: "POST",
          data: {tableName:t_name},
          url: './api_get_sheet.php',
          success: function(data) {
              // console.log(data);
              if(data==""){
                  el.empty(); // remove old options
              }else{
                  data = JSON.parse(data);
                  // console.log(data);
                  var el = $("#roll_sheet");
                  el.empty(); // remove old options
                  var to_add = "";
                  // var to_names = "";
                  to_add += ("<table class='table table-bordered table-striped' id='roll_sheet_table'>");
                  // console.log(data);
                  // console.log(Object.keys(data).length);

                    to_add += '<thead><tr> <th>Name</th> <th>Roll Number</th>';
                    for(var i=2;i<Object.keys(data[0]).length-1;i++){
                      to_add += '<th>'+data[0][i]+'::'+data[1][i]+'</th>';
                    }
                    to_add += '<th>Percentage</th> </tr></thead>';

                    for(var i=2;i<Object.keys(data).length;i++){
                      to_add += ("<tr>");
                      // to_names += "<option value='";
                      // to_names += data[i][0];
                      // to_names += "'>";
                      for(var j=0;j<Object.keys(data[i]).length;j++){
                        to_add += '<td>'+data[i][j]+'</td>';
                      }
                      to_add += ("</tr>");

                    }
                  to_add += ("</table>");
                  // console.log(to_add);
                  el.append(to_add);
                  $('#roll_sheet_data').html(JSON.stringify(data, undefined, 2));
                  // $('#filter_name_list').html(to_names);
                  makeCSV();
              }                                                  
          }
      }); 
  }
    function filter_per_helper(per){
      data = JSON.parse($('#roll_sheet_data').html());
      // console.log(data);
      var el = $("#roll_sheet");
      el.empty(); // remove old options
      var to_add = "";
      // var to_names = "";
      to_add += ("<table class='table table-bordered table-striped' id='roll_sheet_table'>");
      // // console.log(data);
      // // console.log(Object.keys(data).length);

        to_add += '<thead><tr> <th>Name</th> <th>Roll Number</th>';
        for(var i=2;i<Object.keys(data[0]).length-1;i++){
          to_add += '<th>'+data[0][i]+'::'+data[1][i]+'</th>';
        }
        to_add += '<th>Percentage</th> </tr></thead>';

        for(var i=2;i<Object.keys(data).length;i++){
          if(parseFloat(data[i][Object.keys(data[i]).length-1])>per){
            continue;
          }
          // to_names += "<option value='";
          // to_names += data[i][0];
          // to_names += "'>";
          to_add += ("<tr>");
          for(var j=0;j<Object.keys(data[i]).length;j++){
            to_add += '<td>'+data[i][j]+'</td>';
          }
          to_add += ("</tr>");

        }
      to_add += ("</table>");
      el.append(to_add);
      // $('#filter_name_list').html(to_names);
      makeCSV();
    }


  get_code();
 </script>
</html>
