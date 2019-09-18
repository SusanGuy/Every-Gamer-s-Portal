<?php
    require('included_functions.php');
    $connect = db_connection();
    require('session.php');
    verify_login();

     if(isset($_POST['submit'])){
        if(isset($_GET["id"]) && $_GET["id"] !==""){
         
                //User-defined variables
                $g_id=$_GET['id'];
                $p_id=$_GET['p_id'];
                $game_name = $_POST['game_name'];
                $publisher = $_POST['publisher'];
                $platform = $_POST['platform'];
                $genre= $_POST['genre'];
                $date_start = $_POST['date_start'];
                $level_reached= $_POST['level'];
                $total_score=$_POST['score'];
                $c=0;//a variable to check if the game exists or not for the user
                $c1=0;//check if the game exist in the whole database
                $a=0;//a variable to check if the publisher exists or not
                $b=0;
                $var=0;
                
                $b_ID=0;//keep track of the game's id
                $a_ID=0;
                

                
               if(($_SESSION['game_name']!=$game_name)||($_SESSION['publisher']!=$publisher)||($_SESSION['platform_id']!=$platform)||($_SESSION['level']!=$level_reached)||($_SESSION['genre_id']!==$genre)||($_SESSION['date_start']!==$date_start)||($_SESSION['score']!==$total_score)){
                $query= ("SELECT * FROM Users NATURAL JOIN Users_Games NATURAL JOIN Games NATURAL JOIN Publisher WHERE User_ID =".$_SESSION['user_id']." ORDER BY Games.Game_ID");
               	$result_get = $connect->prepare($query);
                $result_get->execute(array('game'=>$game_name));

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

				$query_get_Publisher = "SELECT * FROM Publisher ORDER BY Publisher_ID";
                $result_get_Publisher = $connect->query($query_get_Publisher);
                while($row1 = $result_get_Publisher->fetch()){
                    if(strtolower($row1['Publisher_Name'])==strtolower($publisher)){
                        $a=$row1['Publisher_ID'];


                    }
                    $a_ID=$row1['Publisher_ID'];
                }
                $a_ID=$a_ID+1;
                $b_ID=$b_ID+1;



                if($c1===0 && $c===0){
                    if($a === 0){
                        //insert publisher
                        $query_insert_Publisher = "INSERT into Publisher(Publisher_ID, Publisher_Name) VALUES (:id,:pname)";
                        $result_Publisher = $connect->prepare($query_insert_Publisher);
                        $result_Publisher->execute(array('id'=>$a_ID,'pname'=>$publisher));
                        //insert into games
                        $query_insert_Games = "INSERT INTO Games(Game_ID, Game_Name, Publisher_ID, Genre_ID) VALUES (:gaid,:game,:pid,:gid)";
                        $result1 = $connect->prepare($query_insert_Games);
                        $result1->execute(array('gaid'=>$b_ID,'game'=>$game_name,'pid'=>$a_ID,'gid'=>$genre));
                         

                        //update into Users_Games table
                        $query_insert_UG ="UPDATE Users_Games SET Game_ID=:gaid, level_reached=:level, total_score=:score, date_start=:dstart, Platform_ID=:pid WHERE User_ID=:user_id AND Game_ID=:gameid";
                        $result_insert_UG = $connect->prepare($query_insert_UG);
                        $result_insert_UG->execute(array('gaid'=>$b_ID,'user_id'=>$_SESSION['user_id'],'gameid'=>$g_id,'level'=>$level_reached,'score'=>$total_score,'dstart'=>$date_start,'pid'=>$platform));            

                    }
               			    else{

                         //update into games
                        $query_insert_Games = "INSERT INTO Games(Game_ID, Game_Name, Publisher_ID, Genre_ID) VALUES (:gaid,:game,:pid,:gid)";
                        $result1 = $connect->prepare($query_insert_Games);
                        $result1->execute(array('gaid'=>$b_ID,'game'=>$game_name,'pid'=>$a,'gid'=>$genre));
                         

                        //update into Users_Games table
                        $query_insert_UG ="UPDATE Users_Games SET Game_ID=:gaid, level_reached=:level, total_score=:score, date_start=:dstart, Platform_ID=:pid WHERE User_ID=:user_id AND Game_ID=:gameid";
                        $result_insert_UG = $connect->prepare($query_insert_UG);
                        $result_insert_UG->execute(array('gaid'=>$b_ID,'user_id'=>$_SESSION['user_id'],'gameid'=>$g_id,'level'=>$level_reached,'score'=>$total_score,'dstart'=>$date_start,'pid'=>$platform));         
                        
                    }
                     $_SESSION['message']= "<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Your Game has been updated</center></div>";
                     header('Location: /ssubedi1/CSCI_475/Final Project/yourgames.php');

               }
               else if($c1===1 && $c===0){ 

                if($var==1){
                //if game is already in the game table
                    $query_insert_UG ="UPDATE Users_Games SET Game_ID=:gaid, level_reached=:level, total_score=:score, date_start=:dstart, Platform_ID=:pid WHERE User_ID=:user_id AND Game_ID=:gameid";
                         $result_insert_UG = $connect->prepare($query_insert_UG);
                         $result_insert_UG->execute(array('gaid'=>$b,'user_id'=>$_SESSION['user_id'],'gameid'=>$g_id,'level'=>$level_reached,'score'=>$total_score,'dstart'=>$date_start,'pid'=>$platform)); 
                    $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Your Game has been updated</center></div>";
                    header('Location: /ssubedi1/CSCI_475/Final Project/yourgames.php');
                }
                
                else {
                         $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Cannot update the data! Already existing games cannot have different genre or publisher.</center></div>";
                        header('Location: /ssubedi1/CSCI_475/Final Project/yourgames.php');
                }
                }


                else if($c==1) {         // if game exists in user table

                    if ($game_name==$_SESSION['game_name'] && $publisher==$_SESSION['publisher'] && $genre==$_SESSION['genre_id']){   //only lets you update fields other than game name ,publisher and genre
                         $query_insert_UG ="UPDATE Users_Games SET level_reached=:level, total_score=:score, date_start=:dstart, Platform_ID=:pid WHERE User_ID=:user_id AND Game_ID=:gameid";
                         $result_insert_UG = $connect->prepare($query_insert_UG);
                         $result_insert_UG->execute(array('user_id'=>$_SESSION['user_id'],'gameid'=>$g_id,'level'=>$level_reached,'score'=>$total_score,'dstart'=>$date_start,'pid'=>$platform));   
                         $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Your Game has been updated</center></div>";
                         header('Location: /ssubedi1/CSCI_475/Final Project/yourgames.php');
                     }

                     else{
                        $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>Cannot update the data! Already existing games cannot have different genre or publisher.</center></div>";
                        header('Location: /ssubedi1/CSCI_475/Final Project/yourgames.php');
                     }

                }
            

       }
       else{
             $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>This Game already exists! Cannot update the data!</center></div>";
             header('Location: /ssubedi1/CSCI_475/Final Project/yourgames.php');
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

	<title>Update Game</title>

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
                <li>
                    <a href="yourgames.php">
                        <i class="pe-7s-science"></i>
                        <p>Your Games</p>
                    </a>
                </li>
                <li class="active">
                    <a href="update.php">
                        <i class="pe-7s-note"></i>
                        <p>Update Game</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-panel">
		<nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Update Game</a>
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
                            <a href="/ssubedi1/CSCI_475/Final Project/logout.php">
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
                                <h4 class="title">Update Game</h4>
                            </div>
                            <div class="content">
                                <form method="POST" action="">
                                	<?php
						              	$game_id=$_GET['id'];
						                $query = "SELECT * FROM Users natural join Users_Games natural join Games natural join Publisher natural join Genre natural join Platform where User_ID =:user_id  and Game_ID =:game_id ";
						                $result = $connect->prepare($query);
						                $result->execute(array('user_id'=>$_SESSION['user_id'],'game_id'=>$game_id));
						                if ($result){
						                    $row2 = $result->fetch();
						                    $_SESSION['game_name']=$row2['Game_Name'];
						                    $_SESSION['publisher']=$row2['Publisher_Name'];
                                            $_SESSION['platform']=$row2['Platform_Name'];
                                            $_SESSION['platform_id']=$row2['Platform_ID'];
						                    $_SESSION['date_start']=$row2['date_start'];
						                    $_SESSION['genre']=$row2['Genre_Name'];
						                     $_SESSION['genre_id']=$row2['Genre_ID'];
                                            $_SESSION['genre_id']=$row2['Genre_ID'];
						                    $_SESSION['score']=$row2['total_score'];
						                    $_SESSION['level']=$row2['level_reached'];
						                    	
						                    	}
						                    	?>


                                	
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Game Name</label>
                                                 <input type ="text" class="form-control" 
                                                name="game_name" value = "<?php echo $_SESSION['game_name']; ?>" required>
                                            </div>
                                        </div>

                                    
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Genre</label>
                                                <select name='genre' class="form-control" required>
                                                <?php
                                                    $query_get_Genre = "SELECT * from Genre";
                                                    $result_get_Genre = $connect->query($query_get_Genre);
                                                    $val="";
                                                    while($row1 = $result_get_Genre->fetch()){
                                                    	 if($_SESSION['genre']===$row1['Genre_Name']){
                        								    $val = "selected='selected'";
                      										  }

                                                        echo "<option value='".$row1['Genre_ID']."' $val>".$row1['Genre_Name']."</option>";
                                                        $val="";
                                                    }
                                                ?>
                                            </select>
                                                 </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Publisher</label>
                                                <input type="text" class="form-control" 
                                                name="publisher" value = "<?php echo $_SESSION['publisher']; ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date start</label>
                                                <input type="text" class="form-control" name="date_start" value = "<?php echo $_SESSION['date_start']; ?>" placeholder="YYYY-MM-DD" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Platform</label>
                                                <select name='platform' class="form-control" required>
                                                <?php
                                                    $query_get_Platform = "select * from Platform";
                                                    $result_get_Platform = $connect->query($query_get_Platform);
                                                    while($row3 = $result_get_Platform->fetch()){
                                                    	if($_SESSION['platform']===$row3['Platform_Name']){
                                                    		$val="selected='selected'";
                                                    	}
                                                        echo "<option value='".$row3['Platform_ID']."' $val>".$row3['Platform_Name']."</option>";
                                                        $val="";
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
                                                <input type="number" class="form-control" name="level" value = "<?php echo $_SESSION['level']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Total Score</label>
                                                <input type="number" class="form-control" name="score" value = "<?php echo $_SESSION['score']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name ="submit" class="btn btn-info btn-fill pull-right">Update Game</button>
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
