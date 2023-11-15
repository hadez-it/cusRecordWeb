<?php
session_start();
include "db_conn.php";

if (isset($_POST['uname'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return strtoupper($data);
	}

	$uname = validate($_POST['uname']);

	if (empty($uname)) {
		header("Location: index.php?error=User Name is required");
	    exit();
	} else {
		$sql = "SELECT * FROM accounts WHERE username='$uname'";
		$result = $conn->query($sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['username'] === $uname) {
            	$_SESSION['username'] = $row['username'];
            	$_SESSION['id'] = $row['id'];
				
				// Check if the username is "admin"
				if ($uname === "ADMIN") {
					header("Location: report.php");
					exit();
				} else {	
					header("Location: home.php");
					exit();
				}
            } else {
				header("Location: index.php?error=Incorrect username or password");
		        exit();
			}
		} else {
			header("Location: index.php?error=Incorrect username or password");
	        exit();
		}
	}

} else {
	header("Location: index.php?");
	exit();
}
?>
