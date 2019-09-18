<?php
	function db_connection(){
        require_once('config.php');
		$servername = DBHOST;
        $username = USERNAME;
        $password = PASSWORD;
        $myDB =DBNAME;

		 try {
            $conn = new PDO("mysql:host=$servername;dbname=$myDB", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e) {
            echo "Database Operations Failed: " . $e->getMessage();
        }
        return $conn;
    }
      
    function logout(){
        $_SESSION['user_id']=null;
        $_SESSION['email']=null;
        session_destroy();
        header('Location: index.php');
    }
?>
