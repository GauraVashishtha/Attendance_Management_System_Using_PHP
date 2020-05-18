<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assests/css/lib3.min.css">
    <script type="text/javascript" src="../assests/js/jlib.min.js"></script>
    <script type="text/javascript" src="../assests/js/lib3.min.js"></script>
  <style>
    input[type='radio']:after {
        width: 15px;
        height: 15px;
        border-radius: 0px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #ccc;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }

    input[type='radio']:checked:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -2px;
        left: -1px;
        position: relative;
        background-color: #5bc0de;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 2px solid white;
    }

  </style>
  <link rel="stylesheet" type="text/css" href="../assests/css/main.css">
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
  session_start();
  if (isset($_SESSION['time_table']) && isset($_SESSION['log_table']) && isset($_SESSION['name'])  && isset($_SESSION['user_id']) ){
      $time_table_ = $_SESSION['time_table'];
      $log_table_ = $_SESSION['log_table'];
      $name_ = $_SESSION['name'];
      $user_id_ = $_SESSION['user_id'];
  }else{
    header('Location: ../index.php');
  }

  require_once '../DB/config.php';
  $sql = "SELECT DISTINCT subject from $time_table_";
  $res_subject = $connection->query($sql);
  if(!$res_subject){die("connection Error: ".$connection->error);}

  $sql = "SELECT DISTINCT semester from $time_table_";
  $res_sem = $connection->query($sql);
  if(!$res_sem){die("connection Error: ".$connection->error);}
  
  $sql = "SELECT DISTINCT period from $time_table_";
  $res_prd = $connection->query($sql);
  if(!$res_prd){die("connection Error: ".$connection->error);}
  
  $sql = "SELECT DISTINCT department from $time_table_";
  $res_dept = $connection->query($sql);
  if(!$res_dept){die("connection Error: ".$connection->error);}
  


  if(isset($_POST) && !empty($_POST) && isset($_GET) && !empty($_GET)){
		$ip_error = "";

		$roll_numbers = array();
		foreach ($_POST as $roll=>$val) {
			array_push($roll_numbers, array($roll,$val));
		}
		// [sub] => 'MEIR11' [sec] => '1' [dept] => 'CS' [sem] => '1' [prd] => '1'

		$sub = $_GET['sub'];
		$sec = $_GET['sec'];
		$dept = $_GET['dept'];
		$sem = $_GET['sem'];

		$prd = $_GET['prd'];

		$table_name = $user_id_."_".$sub."_".$dept."_".$sem."_".$sec;

		require_once '../DB/config.php';

		$sql = "INSERT INTO $log_table_ VALUES ('".$table_name."') 
				WHERE NOT EXISTS(SELECT * FROM $log_table_ WHERE sheet_table='$table_name')";
		$sql = "insert into ".$log_table_." 
				 Select '".$table_name."' Where not exists(select * from ".$log_table_." where sheet_table='".$table_name."')
				";
		// echo $sql;
		$res=$connection->query($sql);
		if(!$res){
			$ip_error = $connection->error;
		}
		// print_r($roll_numbers);

		if($connection->affected_rows>0 && $ip_error==""){
			$sql = "CREATE TABLE IF NOT EXISTS ".$table_name."(
             	curr_date varchar(100) ,
             	period varchar(100) ,";
	            foreach ($_POST as $k => $v) {
	            	$sql .= "R_".$k." varchar(100) ,";
	            }
	            $sql .= "CONSTRAINT PK_date_period PRIMARY KEY(curr_date,period)
				 );";

			// echo $sql;

			if(!$connection->query($sql)){$ip_error = "Some connection error occured.";}

			date_default_timezone_set('Asia/Kolkata');

			$sql = "INSERT INTO $table_name (";
    			foreach ($_POST as $k => $v) {
	            	$sql .= "R_".$k." ,";
	            }
			$sql .= "period,curr_date) VALUES (";
    			foreach ($_POST as $k => $v) {
	            	$sql .= "'".$v."' ,";
	            }
	        $sql .= "'$prd','".date('d-m-Y')."');";
	        // echo $sql;

			if(!$connection->query($sql)){$ip_error = "You have already made entry on this date for this period";}
		}else if($ip_error==""){
			date_default_timezone_set('Asia/Kolkata');

			$sql = "INSERT INTO $table_name (";
    			foreach ($_POST as $k => $v) {
	            	$sql .= "R_".$k." ,";
	            }
			$sql .= "period,curr_date) VALUES (";
    			foreach ($_POST as $k => $v) {
	            	$sql .= "'".$v."' ,";
	            }
	        $sql .= "'$prd','".date('d-m-Y')."');";
	        // echo $sql;

			if(!$connection->query($sql)){$ip_error = "You have already made entry on this date for this period";}
		}


        // $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // $actual_link = explode("?", $actual_link)[0];

	
  	if($ip_error!=""){
          echo '<div class="container">
                      <div class="alert alert-danger">
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                          '.$ip_error.'
                      </div>
                  </div>';        
  	}else{
          $_SESSION['mark_att_message'] ='<div class="container">
                      <div class="alert alert-success">
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                          Successfully entry registered.
                      </div>
                  </div>';        
          $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          $actual_link = explode("?", $actual_link)[0];
          header("Location: ./index.php");
  	}
  	$_POST  =array();
  	$_GET = array();
  }


  $connection->close();
?>


	  
	<div class="container text-center">    
	  <div class="row content">
      <h3 class="text-right"><?=$name_;?></h3>
      <hr>
	    <div class="col-sm-12 text-left inner_form"> 

		    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		        <!-- <input type='hidden' name='userId' value='<?=$name_?>'/> -->
		        <div class="form-row">
		            <div class="col-md-1"></div>
		            <div class="col-md-2" style="padding:10px;overflow:hidden;">
		                <label class="col-form-label">Subject</label>
		                <select name="subject" class="form-control" id="subject">
		                  <option value="">Select Subject</option>
		                  <?php 
		                  	while($row=$res_subject->fetch_assoc()){
		                  		$s = $row['subject'];
		                  		echo "<option value='$s'>$s</option>";
		                  	}
		                  ?>
		                </select>
		            </div>
		            <div class="col-md-2" style="padding:10px;overflow:hidden;">
		                <label class="col-form-label">Department</label>
		                <select name="department" class="form-control" id="department" >
		                  <option value="">Select Department</option>
                      <?php 
                        while($row=$res_dept->fetch_assoc()){
                          $s = $row['department'];
                          echo "<option value='$s'>$s</option>";
                        }
                      ?>
		                </select>
		            </div>
		            <div class="col-md-2" style="padding:10px;overflow:hidden;">
		                <label class="col-form-label">Semester</label>
		                <select name="semester" class="form-control" id="semester" >
		                  <option value="">Select Semester</option>
		                  <!-- <option value="1">1</option>
		                  <option value="2">2</option>
		                  <option value="3">3</option>
		                  <option value="4">4</option>
		                  <option value="5">5</option>
		                  <option value="6">6</option>
		                  <option value="7">7</option>
		                  <option value="8">8</option>
                       -->

                      <?php 
                        while($row=$res_sem->fetch_assoc()){
                          $s = $row['semester'];
                          echo "<option value='$s'>$s</option>";
                        }
                      ?>
		                </select>
		            </div>
		            <div class="col-md-2" style="padding:10px;overflow:hidden;">
		                <label class="col-form-label">Period</label>
		                <select name="period" class="form-control" id="period" style="<?php if(isset($_GET['section'])) echo "border-color: #a94442;-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);box-shadow: inset 0 1px 1px rgba(0,0,0,.075);"?>">
		                  <?php if(!isset($_GET['section'])) echo '<option value="">Select Period</option>';?>
<!-- 
		                  <option value="1">1</option>
		                  <option value="2">2</option>
		                  <option value="3">3</option>
		                  <option value="4">4</option>
		                  <option value="5">5</option>
		                  <option value="6">6</option>
		                  <option value="7">7</option>
                       -->
                      <?php 
                        while($row=$res_prd->fetch_assoc()){
                          $s = $row['period'];
                          echo "<option value='$s'>$s</option>";
                        }
                      ?>
		                  <option value="Extra">Extra</option>
		                </select>
		            </div>
		            <div class="col-md-2" style="padding:10px;overflow:hidden;">
		                <label class="col-form-label">Section</label>
		                <select name="sections" class="form-control" id="sec_code">
		                  <option value="">Select Section</option>	
		                </select>
		            </div>
		            <div class="col-md-1"></div>
		        </div>
		        <div  class="form-row" >
		            <div class="col-md-12 text-right">
		                <a href="./index.php" class="btn btn-default">Back</a>
		                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#sheet_table" onclick="get_sheet()">Get Sheet</button>
		            </div>
		        </div>
		    </form>
	    </div>
	  </div>
	</div>

    <div class="wrapper">
        <div class="container" style="width: calc(250px + (800 - 250) * ((100vw - 300px) / (1600 - 300)));">
            <div class="row collapse in" id="sheet_table">
            </div>        
        </div>
    </div>

<footer class="container-fluid text-center" style="margin-top: 50px;">
  <p>Footer Text</p>
</footer>

</body>
<script type="text/javascript">
    $(document).on("change", '#department', get_code);
    $(document).on("change", '#semester', get_code);


    $(document).on('change','#subject',get_sheet);
	$(document).on('change','#department',get_sheet);
	$(document).on('change','#semester',get_sheet);
	$(document).on('change','#sec_code',get_sheet);
	$(document).on('change','#period',get_sheet);

    function get_code() {
            var dept = $('#department').val();
            var sem = $('#semester').val();
            if(dept=="" || sem==""){
                return;
            }
            
            // console.log(dept);
            // console.log(sem);

            $.ajax({
                type: "POST",
                data: {dept: dept,sem: sem},
                url: '../config/get_section.php',
                success: function(data) {
                    if(data==""){
                        var el = $("#sec_code");
                        el.empty(); // remove old options
                    }else{
                        data = JSON.parse(data);
                        // console.log(data);
                        var el = $("#sec_code");
                        el.empty(); // remove old options
                        el.append('<option value="">Select Section</option>');
                        $.each(data, function(key, value) {
                            el.append("<option value='"+value+"'>"+value+"</option>");
                        });   
                    }                                                  
                }
            }); 
    }
	function select_all(){
		$(".roll_number_mark_a").each(function(){
			$(this).attr("checked",false);
		});
		$(".roll_number_mark_p").each(function(){
			$(this).attr("checked",true);
		});
	}
	function deselect_all(){
		$(".roll_number_mark_p").each(function(){
			$(this).attr("checked",false);
		});
		$(".roll_number_mark_a").each(function(){
			$(this).attr("checked",true);
		});
	}
    function get_sheet(){
    	// console.log("Getting");
    	var sub;
    		if($('#subject').val()=="")
    			sub = '<?php if(isset($_GET['subject'])) echo $_GET['subject'];?>';
    		else
    			sub = $('#subject').val();
        var dept;
        	if($('#department').val()=="")
				dept ='<?php if(isset($_GET['department'])) echo $_GET['department'];?>';
        	else
        		dept = $('#department').val();
        var sem;
        	if($('#semester').val()=="")
        		sem = '<?php if(isset($_GET['semester'])) echo $_GET['semester'];?>';
        	else
        		sem = $('#semester').val();
        var sec;
        	if($('#sec_code').val()=="")
        		sec = '<?php if(isset($_GET['section'])) echo $_GET['section'];?>';
        	else
        		sec = $('#sec_code').val();
        var prd;
        	if($('#period').val()=="")
        		prd = 1;
        	else
        		prd = $('#period').val();
  //       console.log(sub);
		// console.log(dept);
		// console.log(sem);
		// console.log(sec);
		// console.log(prd);

        if(dept=="" || sem=="" || sec=="" || sub=="" || prd==""){
            return;
        }
        
        // console.log(dept);
        // console.log(sem);

        $.ajax({
            type: "POST",
            data: {dept: dept,sec: sec,sem: sem},
            url: './api_get_std.php',
            success: function(data) {
                if(data==""){
                    var el = $("#sheet_table");
                    el.empty(); // remove old options
                }else{
                    data = JSON.parse(data);
                    // console.log(data);
                    var el = $("#sheet_table");
                    console.log(el.val());
                    el.empty(); // remove old options
                    var url = window.location.href.split('?')[0];
                    url += "?sub="+sub+"&sec="+sec+"&dept="+dept+"&prd="+prd+"&sem="+sem;
                    console.log(url);
                    to_add = "<h4 class='text-center'><b>Class:- "+dept+" "+sec+" , Subject:- "+sub+" , Period:- "+prd+"</b></h4><hr>";

	                to_add += `<div class="pull-right" ><a href="#" class="btn btn-default" onclick="deselect_all();">All Absent</a> &nbsp;<a href="#" class="btn btn-default" onclick="select_all();">All Present</a></div>`;

         			to_add += "<div style='margin-top: 100px;'><form method='post' action=\""+url+"\">";

                    to_add += `<div class="table-responsive" >
                    <table class='table table-bordered table-striped'>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Roll Number</th>
                                        <th>Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                     // curr_idx = 1;
                    for(var k1 in data){
                    	to_add += "<tr>";
                    	idx = 0;
                    	for(var k2 in data[k1]){
                    		// to_add += "<td>"+(curr_idx++)+"</td>";
                    		to_add += "<td>"+data[k1][k2]+"</td>";
                    		if(idx!=0){
		                    	to_add += "<td> <div style='width: 50%;float: left;'> <input class='roll_number_mark_a' type='radio' name='"+data[k1][k2]+"' value='0'> Absent </div>  <div style='width: 50%;float: right;'> <input class='roll_number_mark_p' type='radio' name='"+data[k1][k2]+"' value='1'> Present </div>  </td>";
                    		}
                    		idx = 1;
                    	}
                    	to_add += "</tr>";
                    }
                    to_add += `</tbody>
								</table>                      
                </div>
                <div>
									<input type="submit" class="btn btn-info pull-right" value="Submit" id="sheet_table_ip">
                </div>
								 </form></div>`;
					// console.log(to_add);
					el.append(to_add);
					select_all();
                }                                                  
            }
        }); 
    }

get_sheet();

 //  if(isset($_GET) && isset($_GET['subject']) && isset($_GET['department']) && isset($_GET['semester']) && isset($_GET['section']) && isset($_GET['id'])){
 //  	echo "YEAH";
 // //  	echo ($_GET['subject']."<hr>");
	// // echo ($_GET['department']."<hr>");
	// // echo ($_GET['semester']."<hr>");
	// // echo ($_GET['section']."<hr>");
	// // echo ($_GET['id']."<hr>");



 //  }




 </script>
</html>
