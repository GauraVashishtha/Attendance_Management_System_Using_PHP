<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./assests/css/lib3.min.css">
    <script type="text/javascript" src="./assests/js/jlib.min.js"></script>
    <script type="text/javascript" src="./assests/js/lib3.min.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {
    	height: 950px;
    }
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 30px;
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }

    .text-block h1{
      text-align: center;
      margin-top: 20px;
      margin-bottom: 30px;
      color: #333;
    }
    .text-block p{
      text-align: justify;
      margin-top: 20px;
      margin-bottom: 30px;
    }

  </style>
  <link rel="stylesheet" type="text/css" href="./assests/css/main.css">
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
        <li><a href="./authorize.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        <li><a href="./register.php"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
        <!-- <li><a href="./tt_loader/register.php"> Register </a></li> -->
      </ul>
    </div>
  </div>
</nav>
  
    <?php
        session_start();
        if(isset($_SESSION) && isset($_SESSION['message'])){
            echo    '<div class="container" style="position: absolute;z-index: 100;margin: auto;transform: translate(10%);">
                        <div class="alert alert-info">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            '.$_SESSION['message'].'
                        </div>
                    </div>';
        }
        session_unset();
        session_destroy();
    ?>


<div class="container-fluid" style="padding: 0px;">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
      <li data-target="#myCarousel" data-slide-to="3"></li>
      <li data-target="#myCarousel" data-slide-to="4"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <div class="item active">
        <img src="https://nitkkr.ac.in/images/gallery/15aug2019.JPG" alt="Los Angeles" style="width:100%;height: 600px;">
      </div>

      <div class="item">
        <img src="https://nitkkr.ac.in/images/gallery/2r.JPG" alt="Chicago" style="width:100%;height: 600px;">
      </div>
    
      <div class="item">
        <img src="https://nitkkr.ac.in/images/gallery/DSC_9061.JPG" alt="New york" style="width:100%;height: 600px;">
      </div>

      <div class="item">
        <img src="https://nitkkr.ac.in/images/gallery/DSC_8685.JPG" alt="New york" style="width:100%;height: 600px;">
      </div>
      
      <div class="item">
        <img src="https://nitkkr.ac.in/images/gallery/DSC_8681.JPG" alt="New york" style="width:100%;height: 600px;">
      </div>

    </div>

  </div>
</div>

  
<div class="container-fluid text-center">    
  <div class="row content">
    <div class="col-sm-2 sidenav">
      <!-- <p><a href="#">Link</a></p>
      <p><a href="#">Link</a></p>
      <p><a href="#">Link</a></p> -->
    </div>
    <div class="col-sm-8 text-left text-block" style="padding-top: 50px;"> 
      <h1>Vision of the Institute</h1>
      <p>To be a role-model in technical education and research, responsive to global challenges.</p>
      <hr>

      <h1>Mission of the Institute</h1>
      <p><ul>
        <li>To impart quality technical education that develops innovative professionals and entrepreneurs</li>
        <li>To undertake research that generates cutting-edge technologies and futuristic knowledge, focusing on the socio-economic needs</li></ul>
      </p>
      <hr>

      <h1>CAMPUS</h1>
      <p>
        The campus extends over an area of 300 acres imaginatively laid down on a picturesque landscape. It presents a spectacle of harmony in architecture and natural beauty. The campus has been organised into three functional sectors:

        Hostels for the students,

        Instructional buildings and Residential sector for the staff.

        Hostels for students are located towards Eastern side of the campus in the form of cluster. Three storey buildings of hostels provide comfortable accommodation and pleasing environment to students.

        The instructional buildings have been located between the two residential sectors in order to reduce walking distance. A full fledged health centre manned by qualified doctors, a Post Office and a branch of the State Bank of India are located at convenient points on the Campus.  
      </p>

      <hr>
      <h1>ADMINISTRATION</h1>
      <p>In the administration of the Institute, the Director is the Principal Academic and Executive Officer of the Institute and is responsible for the proper administration of the Institute and for imparting instructions and maintenance of discipline therein. He is assisted in his day to day work by Deans, Chairmen of the Departments, Professor-in-Charges, Registrar and other Officers and various committees of the Institute.</p>

    </div>
    <div class="col-sm-2 sidenav">
     
    </div>
  </div>
</div>

<footer class="container-fluid text-center">
  <p></p>
</footer>

</body>
<script type="text/javascript">
    
 </script>
</html>
