<?php
        session_start();
        include "connection.php";

        if(isset($_POST["email"]) && $_POST["email"] != ""){
		$email = $_POST["email"];
	}else{
		die("Don't try to mess around bro email ;)");
	}

	if(isset($_POST["password"]) && $_POST["password"] != ""){
		$password = hash('sha256', $_POST['password']);
	}else{
		die("Don't try to mess around bro password;)");
	}

        $x = $connection->prepare("SELECT * FROM `users` WHERE `user_password`= ? AND `user_email`=?");
        $x->bind_param("ss",$password,$email);
        $x->execute();
        $result = $x->get_result();
        $user = $result->fetch_assoc();
    
        if($user)
        {
            $_SESSION['user_id'] = $user['user_id'];
            header("Location:../homepage.php");
        }
        else{
            header("Location:../index.html");
        }
?>