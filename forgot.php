<?php
include('included_functions.php');
   $connect = db_connection();

include_once("session.php");
    if(logged_in()){
    	$_SESSION['message'] = "<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>You are already logged in!</center></div>";
    	header('Location:table.php');
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Custom Theme files -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- //Custom Theme files -->
<!-- web font -->
<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
<!-- //web font -->
</head>
<style>
	body{
		background-image: url('images/spiderman.jpg');
	}
</style>
<body>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1 style="color:black">Reset your password</h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="#" method="post">
					<input class="text email" type="email" name="email" placeholder="Email" required="">
					<div class="wthree-text">
						<div class="clear"> </div>
					</div>
					<input type="submit" value="Reset Password">
				</form>
				<p>I remember my password! <a href="index.php"> Login Now!</a></p>
			</div>
		</div>
		<ul class="colorlib-bubbles">
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
	<!-- //main -->
</body>
</html>