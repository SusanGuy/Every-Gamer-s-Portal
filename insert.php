<?php
 require('included_functions.php');
  require('session.php');
  verify_login();
    $connect = db_connection();
    $message = message();

    if(isset($_POST['submit'])){
        if(isset($_POST['game_name'])){
                //User-defined variables
                $game_name = $_POST['game_name'];
                $publisher = $_POST['publisher'];
                $platform = $_POST['platform'];
                $genre= $_POST['genre'];
                $date_start = $_POST['date_start'];
                $level_reached= $_POST['level'];
                $total_score=$_POST['score'];

                $query= ("SELECT * FROM Users NATURAL JOIN Users_Games NATURAL JOIN Games WHERE User_ID =".$_SESSION['user_id']." ORDER BY Games.Game_ID");
                $query_get_Publisher = "SELECT * FROM Publisher ORDER BY Publisher_ID";
                $result_get = $connect->prepare($query);
                $result_get->execute(array('game'=>$game_name));
                $c=0;//a variable to check if the game exists or not for the user
                $c1=0;//check if the game exist in the whole database
                $a=0;//a variable to check if the publisher exists or not
                $b=0;
                $b_ID=0;//keep track of the game's id
                $a_ID=0;//keep track of the publisher's id
                $var=0;
                while($row = $result_get->fetch()){
                    if(strtolower($row['Game_Name'])==strtolower($game_name)){
                        $c=1;
                    }
                }

                  $result_games=$connect->query("SELECT * FROM Games natural join Genre natural join Publisher ORDER BY Game_ID ");
                  while($row = $result_games->fetch()){
                    if(strtolower($row['Game_Name'])===strtolower($game_name)){
                        if(strtolower($row['Publisher_Name'])===strtolower($publisher) && $row['Genre_ID']==$genre){
                                    $var=1;
                        }
                             $c1=1;
                             $b=$row['Game_ID'];
                    }



                      $b_ID=$row['Game_ID'];
                   
                }
                
                $result_get_Publisher = $connect->query($query_get_Publisher);
                while($row1 = $result_get_Publisher->fetch()){
                    if(strtolower($row1['Publisher_Name'])==strtolower($publisher)){
                        $a=$row1['Publisher_ID'];
                    }
                    $a_ID=$row1['Publisher_ID'];
                }
                $a_ID=$a_ID+1;
                $b_ID=$b_ID+1;

                if($c === 1){
                    $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Game already exists</center></div>";
                    header('Location: /~ssubedi1/CSCI_475/Final Project/yourgames.php');
                }
                else if($c1===0 && $c===0){
                    if($a === 0){
                        //insert into publisher
                        $query_insert_Publisher = "INSERT into Publisher(Publisher_ID, Publisher_Name) VALUES (:id,:pname)";
                        $result_Publisher = $connect->prepare($query_insert_Publisher);
                        $result_Publisher->execute(array('id'=>$a_ID,'pname'=>$publisher));
                        //insert into games
                        $query_insert_Games = "INSERT INTO Games(Game_ID, Game_Name, Publisher_ID, Genre_ID) VALUES (:gaid,:game,:pid,:gid)";
                        $result1 = $connect->prepare($query_insert_Games);
                        $result1->execute(array('gaid'=>$b_ID,'game'=>$game_name,'pid'=>$a_ID,'gid'=>$genre));

                        //insert into Users_Games table
                       $query_insert_UG ="INSERT INTO Users_Games(User_ID, Game_ID, date_start,level_reached,total_score,Platform_ID) VAlUES (:user_id,:gameid,:dstart,:level,:score,:plid)";
                        $result_insert_UG = $connect->prepare($query_insert_UG);
                        $result_insert_UG->execute(array('user_id'=>$_SESSION['user_id'],'gameid'=>$b_ID,'dstart'=>$date_start,'level'=>$level_reached,'score'=>$total_score,'plid'=>$platform));
                        

                    }
                    else{

                    //insert into games

                        $query_insert_Games = "INSERT INTO Games(Game_ID, Game_Name, Publisher_ID, Genre_ID) VALUES (:gaid,:game,:pid,:gid)";
                        $result1 = $connect->prepare($query_insert_Games);
                        $result1->execute(array('gaid'=>$b_ID,'game'=>$game_name,'pid'=>$a,'gid'=>$genre));

                        //insert into Users_Games table
                       $query_insert_UG ="INSERT INTO Users_Games(User_ID, Game_ID, date_start,level_reached,total_score,Platform_ID) VAlUES (:user_id,:gameid,:dstart,:level,:score,:plid)";
                        $result_insert_UG = $connect->prepare($query_insert_UG);
                        $result_insert_UG->execute(array('user_id'=>$_SESSION['user_id'],'gameid'=>$b_ID,'dstart'=>$date_start,'level'=>$level_reached,'score'=>$total_score,'plid'=>$platform));
                        
                    }
                    $_SESSION['message']= "<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Your Game has been added</center></div>";
                    header('Location: /~ssubedi1/Final Project/yourgames.php');

                }
                else if($c1===1 && $c===0){
                if($var==1){ //if game is already in the game table
                    $query_insert_UG ="INSERT INTO Users_Games(User_ID, Game_ID, date_start,level_reached,total_score,Platform_ID) VAlUES (:user_id,:gameid,:dstart,:level,:score,:plid)";
                        $result_insert_UG = $connect->prepare($query_insert_UG);
                        $result_insert_UG->execute(array('user_id'=>$_SESSION['user_id'],'gameid'=>$b,'dstart'=>$date_start,'level'=>$level_reached,'score'=>$total_score,'plid'=>$platform));
                    $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Your Game has been added</center></div>";
                    header('Location: /~ssubedi1/Final Project/yourgames.php');
                }
                else{
                         $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Cannot insert the data!Already existing games cannot have different genre or publisher.</center></div>";
                        header('Location: /~ssubedi1/Final Project/yourgames.php');

                }
                }
        }
    }

   
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Add Games</title>

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
                </li>
                <li>
                    <a href="table.php">
                        <i class="pe-7s-note2"></i>
                        <p>Table List</p>
                    </a>
                </li>
           <li class="active">
                    <a href="insert.php">
                        <i class="pe-7s-plus"></i>
                        <p>Add Games</p>
                    </a>
                </li>
                <li>
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
                    <a class="navbar-brand" href="#">New Game?</a>
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
                            <a href="/~ssubedi1/Final Project/logout.php">
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
                                <h4 class="title">Add a Game</h4>
                            </div>
                            <div class="content">
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Game Name</label>
                                                 <input type ="text" class="form-control" 
                                                name="game_name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Genre</label>
                                                <select name='genre' class="form-control" required>
                                                <?php
                                                    $query_get_Genre = "select * from Genre";
                                                    $result_get_Genre = $connect->query($query_get_Genre);
                                                    while($row = $result_get_Genre->fetch()){
                                                        echo "<option value='".$row['Genre_ID']."'>".$row['Genre_Name']."</option>";
                                                    }
                                                ?>
                                            </select>
                                                 </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Publisher</label>
                                                <input type="text" class="form-control" 
                                                name="publisher" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date start</label>
                                                <input type="text" class="form-control" name="date_start" placeholder="YYYY-MM-DD" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Platform</label>
                                                <select name='platform' class="form-control" required>
                                                <?php
                                                    $query_get_Platform = "select * from Platform";
                                                    $result_get_Platform = $connect->query($query_get_Platform);
                                                    while($row = $result_get_Platform->fetch()){
                                                        echo "<option value='".$row['Platform_ID']."'>".$row['Platform_Name']."</option>";
                                                    }
                                                ?>
                                            </select>
                                            </div>
                                        </div>
                                       
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Level Reached</label>
                                                <input type="number" class="form-control" name="level" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Total Score</label>
                                                <input type="number" class="form-control" name="score" required>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" name ="submit" class="btn btn-info btn-fill pull-right">Add Game</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
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
