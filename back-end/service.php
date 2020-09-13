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
            $_SESSION['username']=$username;
            $_SESSION['logintime']=date("d/m/Y H:i:s");
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
        if(!empty($_SESSION['username'])){
            $contents = '<div>
            <p>Hai '.$_SESSION['username'].', waktu login anda '.$_SESSION['logintime'].'</p>
            </div>';
            echo json_encode(array("statusCode"=>200,"content"=>$contents));
        }else{
			echo json_encode(array("statusCode"=>201));
		}
    }
?>
  