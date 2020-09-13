<?php
	include 'database.php';
	session_start();
	if($_POST['type']==1){
		$username=$_POST['username'];
        $password=md5($_POST['password']);
        $password2=md5($_POST['password2']);
		
		$duplicate=mysqli_query($conn,"select * from users where username='$username'");
		if (mysqli_num_rows($duplicate)>0)
		{
			echo json_encode(array("statusCode"=>201));
        } 
        elseif($password != $password2){
            echo json_encode(array("statusCode"=>202));
        } 
        else{
			$sql = "INSERT INTO `users`( `username`, `password`) 
			VALUES ('$username', '$password')";
			if (mysqli_query($conn, $sql)) {
				echo json_encode(array("statusCode"=>200));
			} 
			else {
				echo json_encode(array("statusCode"=>201));
			}
		}
		mysqli_close($conn);
	}
	else if($_POST['type']==2){
		$username=$_POST['username'];
		$password=md5($_POST['password']);
		$check=mysqli_query($conn,"select * from users where username='$username' and password='$password'");
		if (mysqli_num_rows($check)>0)
		{
            $lastlogin = $_SERVER['HTTP_USER_AGENT'];
            $sql = "UPDATE `users` SET `lastlogin`='$lastlogin' WHERE username='$username'";
		    mysqli_query($conn, $sql);
            $_SESSION['username']=$username;
            $_SESSION['logintime']=date("d/m/Y H:i:s");
            $_SESSION['browser']=$_SERVER['HTTP_USER_AGENT'];
			echo json_encode(array("statusCode"=>200));
		}
		else{
			echo json_encode(array("statusCode"=>201));
		}
		mysqli_close($conn);
    }
    else if($_POST['type']==3){
        unset($_SESSION["username"]);
        unset($_SESSION["logintime"]);
        echo json_encode(array("statusCode"=>200));
    }
    else if($_POST['type']==4){
        $username=$_SESSION['username'];
        $lastlogin = $_SERVER['HTTP_USER_AGENT'];
        $check=mysqli_query($conn,"select * from users where username='$username' and lastlogin='$lastlogin'");
        if(!empty($_SESSION['username']) && mysqli_num_rows($check)>0){
            $contents = '<div>
            <p>Hai '.$_SESSION['username'].', waktu login anda '.$_SESSION['logintime'].'</p>
            </div>';
            echo json_encode(array("statusCode"=>200,"content"=>$contents));
        }else if(!empty($_SESSION['username']) && $_SESSION['browser']!=$_SERVER['HTTP_USER_AGENT']){
            unset($_SESSION["username"]);
            unset($_SESSION["logintime"]);
            unset($_SESSION["browser"]);
            echo json_encode(array("statusCode"=>201));
        }else{
			echo json_encode(array("statusCode"=>201));
		}
    }
?>
  