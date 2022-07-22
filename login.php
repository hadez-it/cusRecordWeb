<?php
session_start();
include "db_conn.php";

if (isset($_POST['uname']) /*&& isset($_POST['password'])*/) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return strtoupper($data);
	}

	$uname = validate($_POST['uname']);
	//$pass = validate($_POST['password']);

	if (empty($uname)) {
		header("Location: index.php?error=User Name is required");
	    exit();
	}else{
		$sql = "SELECT * FROM accounts WHERE username='$uname'";
		//$sql = "SELECT * FROM accounts WHERE username='admin' AND password='admin'";

		$result = $conn->query($sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['username'] === $uname) {
            	$_SESSION['username'] = $row['username'];
            	//$_SESSION['name'] = $row['name'];
            	$_SESSION['id'] = $row['id'];
            	header("Location: home.php");
		        exit();
            }else{
				header("Location: index.php?error=Incorect User name or password");
		        exit();
			}
		}else{
			header("Location: index.php?error=Incorect User name or password");
	        exit();
		}
	}

}else{
	header("Location: index.php?");
	exit();
}
?>
