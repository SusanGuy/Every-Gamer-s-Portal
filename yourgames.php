<?php
include('included_functions.php');
$connect = db_connection();
require_once("session.php");
$message = message();
        if($message){
            echo $message;
        }
verify_login();
        


?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>User's Games</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="purple" data-image="assets/img/sidebar-5.jpg">

    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->


    
        <div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                   <?php 
                   echo $_SESSION['fname'].' '.$_SESSION['lname'];
                   ?>
                </a>
            </div>

            <ul class="nav">
                <li>
                    <a href="table.php">
                        <i class="pe-7s-note2"></i>
                        <p>Table List</p>
                    </a>
                </li>
           <li>
                    <a href="insert.php">
                        <i class="pe-7s-plus"></i>
                        <p>Add Games</p>
                    </a>
                </li>
                <li class="active">
                    <a href="yourgames.php">
                        <i class="pe-7s-science"></i>
                        <p>Your Games</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-panel">
		<nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Games</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-dashboard"></i>
								<p class="hidden-lg hidden-md">Dashboard</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="">
                               <p>Account</p>
                            </a>
                        </li>
                        
                        <li>
                            <a href="/~ssubedi1/CSCI_475/Final Project/logout.php">
                                <p>Log out</p>
                            </a>
                        </li>
						<li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                           <div class="header">
                                <h4 class="title">Your Game List</h4>
                            </div>
                                
                            <?php
                                
                                 $query= "SELECT * from Users_Games natural join Games natural join Publisher natural join Genre natural join Platform where User_ID =:user_id ORDER BY Users_Games.GAME_ID DESC";
                                    $stmt = $connect -> prepare($query);
                                    $stmt -> execute(array('user_id'=>$_SESSION['user_id']));

                                    if ($stmt) {
                                            echo "<div class='content table-responsive table-full-width'>";
                                            echo "<table class='table table-hover table-striped'>";
                                            echo "<thead>";
                                            echo "<th>Game</th>";
                                            echo "<th>Publisher</th>";
                                            echo "<th>Genre</th>";
                                            echo "<th>Platform</th>";
                                            echo "<th>Date Start</th>";
                                            echo "<th>Level Reachd</th>";
                                            echo "<th>Total Score</th>";
                                             echo "</thead>";
                                            while($row = $stmt->fetch()) {
                                            echo "<tr>";
                                            echo "<td>"." ".$row["Game_Name"]."</td>";
                                            echo "<td>"." ".$row["Publisher_Name"]."</td>";
                                            echo "<td>"." ".$row["Genre_Name"]."</td>";
                                            echo "<td>"." ".$row["Platform_Name"]."</td>";
                                            echo "<td>"." ".$row["date_start"]."</td>";
                                            echo "<td>"." ".$row["level_reached"]."</td>";
                                            echo "<td>"." ".$row["total_score"]."</td>";
                                            echo "<td><a href='update.php?id=".urlencode($row["Game_ID"])."&p_id=".urlencode($row["Publisher_ID"])."'>Edit</a></td>"; 
                                            echo "<td><a style='color:#FF0022;' href='delete.php?id=".urlencode($row["Game_ID"])."' onclick ='return confirm('Are you sure you want to delete?');'>Delete</a></td>";
                                            echo "</tr>";
                                            
                                        }
                                            echo "</tbody>";
                                            echo "</table>";

                                            echo "</div>";
                                        
                                    }

?>
  </div>
</div>


</body>

    <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/chartist.min.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>
	<script src="assets/js/demo.js"></script>

</html>
