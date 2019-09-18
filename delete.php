<?php

    include('included_functions.php');
    include('session.php');
    $connect = db_connection();
    verify_login();
    if(isset($_GET["id"]) && $_GET["id"] !==""){
        $g_id = $_GET["id"];
        $query = "DELETE FROM Users_Games WHERE User_ID = :user_id AND Game_ID = :game_id";
        $result = $connect->prepare($query);
        $check =$result->execute(array('user_id'=>$_SESSION['user_id'],'game_id'=>$g_id));
        if($check){
            $_SESSION['message']="<div style='background-color:#42b0f4; font-size: 20px'; class='alert-login'><center>The Game has been deleted</center></div>";
        }else{
            $_SESSION['message']="<div><center>Error! Couldn't delete the Game</center></div>";
        }
           header('Location: /~ssubedi1/Final Project/yourgames.php');
    }
?>