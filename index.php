<?php
include('included_functions.php');
   $connect = db_connection();

 require_once('session.php');
 
  $message = message();
        if($message){
            echo $message;
        }
  if(logged_in()){
      $_SESSION['message'] = "<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>You are already logged in!</center></div>";
      header('Location:table.php');
    }
      
    ?>

  <?php
	if(isset($_POST['login'])) {
		$errMsg = '';
		$email = $_POST['email'];
		$password = $_POST['password'];
		if($email == '')
			$errMsg = 'Enter email';
		if($password == '')
			$errMsg = 'Enter password';
		if($errMsg == '') {
			try {
				$stmt = $connect->prepare('SELECT User_ID, User_FName, User_LName, email, password FROM Users WHERE email = :email');
				$stmt->execute(array(
					':email' => $email
					));
				$data = $stmt->fetch(PDO::FETCH_ASSOC);
				if($data == false){
					$error = "User $email not found.";
				}
				else {
					if(crypt($password, $data['password']) == $data['password'])  {
						$_SESSION['user_id'] = $data['User_ID'];
						$_SESSION['email'] = $data['email'];
						$_SESSION['fname'] = $data['User_FName'];
                  $_SESSION['lname'] = $data['User_LName'];
						header('Location: /~ssubedi1/CSCI_475/Final Project/table.php');
						exit;
					}
					else
						$error = 'Password do not match.';
				}
			}
			catch(PDOException $e) {
				$errMsg = $e->getMessage();
			}
		}
	}
?>

   <!DOCTYPE html>
<html lang="en">
<head>
   <title>Every Gamer's Portal</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->   
   <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
   
   <link href="https://fonts.googleapis.com/css?family=Luckiest+Guy" rel="stylesheet">
   
<!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->   
   <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->   
   <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
   <link rel="stylesheet" type="text/css" href="css/util.css">
   <link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<style>
   .title{
  font-size: 50px;
  font-family: 'Luckiest Guy';
  padding: 10px;
  margin-left: 50px;
  color: #fff;
}</style>
<body style="background-color: #666666;">
   
   <div class="limiter">
      <div class="container-login100">
         <div class="wrap-login100">
            <form action = "" method = "post" class="login100-form validate-form">
               <span class="login100-form-title p-b-43">
                  Login to continue
               </span>
               
               
               <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                  <input class="input100" type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>">
                  <span class="focus-input100"></span>
                  <span class="label-input100">Email</span>
               </div>
               
               
               <div class="wrap-input100 validate-input" data-validate="Password is required">
                  <input class="input100" type="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>" >
                  <span class="focus-input100"></span>
                  <span class="label-input100">Password</span>
               </div>

               <div class="flex-sb-m w-full p-t-3 p-b-32">
                  <div>
                     <a href="forgot.php" class="txt1">
                        Forgot Password?
                     </a>
                  </div>
               </div>
         

               <div class="container-login100-form-btn"> 
                  <button type="submit" name="login" class="login100-form-btn">
                     Login
                  </button>
               </div>
               <div style="margin-left:10px"class="container-login100-form-btn">
                  Don't have an account?  <a href="signup.php">&nbsp; Sign Up</a>
               </div>
                <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>


            </form>

            <div class="login100-more" style="background-image: url('images/Apex.jpg');">
               <div class= "title">
                  Every Gamer's Portal
               </div>
            </div>
         </div>
      </div>
   </div>
   
   

   
   
<!--===============================================================================================-->
   <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
   <script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
   <script src="vendor/bootstrap/js/popper.js"></script>
   <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
   <script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
   <script src="vendor/daterangepicker/moment.min.js"></script>
   <script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
   <script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
   <script src="js/main.js"></script>

</body>
</html>

