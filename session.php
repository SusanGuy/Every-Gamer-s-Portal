<?php
    session_start();

    function message() {
        if ($_SESSION['message'] !== ""){
            $message =$_SESSION['message'];
            $_SESSION['message'] = "";
            return $message;
        }
    }
    
    function verify_login(){
        if((!isset($_SESSION['email']) && $_SESSION['email'] === null) && (!isset($_SESSION['user_id']) && $_SESSION['user_id'] === null)){
            $_SESSION['message'] = "<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>You must login first!</center></div>";
            header('Location:index.php');
            exit;
        }

    }

    function logged_in(){
        if((!isset($_SESSION['email']) && $_SESSION['email'] === null) && (!isset($_SESSION['user_id']) && $_SESSION['user_id'] === null)){
            return false;
        }else{
            return true;
        }
    }
    
     
?>