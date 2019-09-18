<?php
include('included_functions.php');
   $connect = db_connection();

include_once("session.php");
    if(logged_in()){
    	$_SESSION['message'] = "<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>You are already logged in!</center></div>";
    	header('Location:table.php');
    }
?>

<?php
   if(isset($_POST['SIGNUP'])) {
		$errMsg = '';
		// Get data from FROM
		$fname = $_POST['firstname'];
		$lname = $_POST['lastname'];
		$email = $_POST['email'];
		$pass = $_POST['password'];
		$repass = $_POST['re-password'];
		
			try {
			$query = "SELECT * from Users";
            $result = $connect->query($query);
            $c=0;
            while($row = $result->fetch()){
                if($row['email']==$email){
                    $c = 1;
                }
                $id = $row['User_ID'];
            }
            $id = $id + 1;

            	if ($c==0){
            		if($pass != $repass){
						$errMsg = "Password didn't match";
						
					}
					else{
					$hashed_password=crypt($pass);
					$stmt = $connect->prepare('INSERT INTO Users (User_ID,User_FName, User_LName, email, password) VALUES (:id,:fname, :lname, :email, :password)');
					$stmt->execute(array(
					':id' => $id,
					':fname' => $fname,
					':lname' => $lname,
					':email' => $email,
					':password' => $hashed_password
					));
				
				header('Location: signup.php?action=joined');
				exit;
			}
			}
			else{
				$errMsg = "Email already exists!";
			}

			}

			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
	

	if(isset($_GET['action']) && $_GET['action'] == 'joined') {
		$successMsg = 'Registration successfull. Now you can <a href="index.php" style="color: red">Login!</a>';
	}
?>

<!DOCTYPE html>
<html>
<head>
<title>SignUp Form</title>
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
<body>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1 style="color:black">SignUp Form</h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="signup.php" method="post">
					<input class="text" type="text" name="firstname" placeholder="First Name" required=""><br>
					<input class="text" type="text" name="lastname" placeholder="Last Name" required="">
					<input class="text email" type="email" name="email" placeholder="Email" required="">
					<input class="text" type="password" name="password" placeholder="Password" required="">
					<input class="text w3lpass" type="password" name="re-password" placeholder="Confirm Password" required="">
					<input type="submit" name="SIGNUP">
				</form>
				<p>Already have an Account? <a href="index.php"> Login Now!</a></p>
				<div style = "font-size:18px; color:#DF0054; margin-top:10px; text-align:center;"><?php echo $errMsg; ?></div>
				<div style = "font-size:18px; color:#ACEACF; margin-top:10px; text-align:center;"><?php echo $successMsg; ?></div>
			</div>
		</div>
		<!-- copyright -->
		<div class="colorlibcopy-agile">
			<p>© 2018 Susan Subedi Signup Form. All rights reserved | Design by <a href="https://getbootstrap.com" target="_blank">Bootstrap</a></p>
		</div>
		<!-- //copyright -->
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