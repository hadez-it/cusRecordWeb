<!DOCTYPE html>
<html>

<head>
	<title>LOGIN</title>
	<link rel="stylesheet" type="text/css" href="style3.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>
	<form action="login.php" method="post">
		<h1>Log in</h1>
		<div class="inset">
			<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
			<p>
				<label for="uname">Username</label>
				<input type="text" name="uname" id="uname" STYLE="color:#FFFFFF; text-transform: uppercase;">
			</p>
		</div>
		<p class="p-container">
			<input type="submit" name="go" id="go" value="Log in">
		</p>
	</form>
</body>

</html>
