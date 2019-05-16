<?php
	session_start();
	
	if(end($_SESSION['previousPage']) != "login.php") {
		array_push($_SESSION['previousPage'],"login.php");	
	}
	
		if(isset($_SESSION['registered'])) {
			unset($_SESSION['registered']);

			$message = "Successfully registered! Please check your email for your access code.";
			echo "<script type='text/javascript'>alert('";
			echo trim($message);
			echo "');</script>";		
	}
	
	//XML data of images and text to display
$general = simplexml_load_file("Resources/general_" . $_SESSION['lang'] . ".xml") or die("Error: Could not load the module xml data!");

	//Auto Login
	if(isset($_COOKIE["login"]) AND isset($_COOKIE["verifyLogin"])) {
		$id = $_COOKIE["login"];
		$mysqli = new mysqli("localhost", "uwai", "integrity17", "uwai");

		$sql = "SELECT email, access_code FROM tbl_student WHERE id = '$id'";
				$result = $mysqli->query($sql);

		$row = $result->fetch_assoc();
		$accessCode = $row['access_code'];
		$email = $row['email'];
		if (hash("sha256",$accessCode . $email) == $_COOKIE["verifyLogin"]) {
			$_SESSION['id'] = $_COOKIE["login"];
			
				$id = $_SESSION['id'];
				$sql = "Update tbl_student SET last_login = now() WHERE id = '$id'";
				$r = $mysqli->query($sql);
				
            
			if ($row['pre_test_complete']==1) {
               header("Location: /uwai/home.php");
                // header("Location: /uwai/notice.php");
			}
			else {
               header("Location: /uwai/infoletter.php");
                // header("Location: /uwai/notice.php");
            }
		}
	}
	
	//Check login credentials function
	function login($id, $password) {
		global $general;
	
		$mysqli = new mysqli("localhost", "uwai", "integrity17", "uwai");

		$sql = "SELECT id, access_code, pre_test_complete FROM tbl_student WHERE email = '$id'";

		$result = $mysqli->query($sql);

		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			if ($password == $row['access_code']) {
				$_SESSION['id'] = $row['id'];
				$_SESSION['verifyId'] = hash("sha256",$password . $id);
				$_SESSION['guest'] = false;
				unset($_SESSION['guest']);
				
				setcookie("login", $_SESSION['id'], time() + (86400 * 30), "/");
				
				$id = $_SESSION['id'];
				$sql = "SELECT email, access_code FROM tbl_student WHERE id = '$id'";
				$res = $mysqli->query($sql);
				$r = $res->fetch_assoc();
				$accessCode = $r['access_code'];
				$email = $r['email'];
				setcookie("verifyLogin", hash("sha256",$accessCode . $email), time() + (86400 * 30), "/");

				$id = $_SESSION['id'];
				$sql = "Update tbl_student SET last_login = now() WHERE id = '$id'";
				$res = $mysqli->query($sql);
				
				
				if ($row['pre_test_complete']==1) {
					echo '<script>
				$.mobile.changePage("home.php",{transition:"flow"});
				</script>';
				}
				else {
					echo '<script>
				$.mobile.changePage("infoletter.php",{transition:"flow"});
				</script>';
				}
			}
			else {
				//echo "That password is incorrect";
				$message = $general->incorrect;
				echo "<script type='text/javascript'>alert('";
				echo trim($message);
				echo "');</script>";
			}
		}
		else {
			//echo "That student email does not exist";
			$message = $general->doesntExist;
			echo "<script type='text/javascript'>alert('";
			echo trim($message);
			echo "');</script>";
		}

		mysqli_close($mysqli);
	}
	

	
if (isset($_POST['prev'])) {
$_SESSION["loggedOut"] = true;
		setcookie("login", "0", time() - (86400 * 30), "/");
		setcookie("verifyLogin", "0", time() - (86400 * 30), "/");
		setcookie("lang", "0", time() - (86400 * 30), "/");
		unset($_COOKIE["verifyLogin"]);
		unset($_COOKIE["login"]);
		unset($_COOKIE["lang"]);
		header("Location: /uwai/index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<!-------jQuery Mobile Header Data------->
		<!-- Include meta tag to ensure proper rendering and touch zooming -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Include jQuery Mobile stylesheets -->
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

		<!-- Include the jQuery library -->
		<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

		<!-- Include the jQuery Mobile library -->
		<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	
	<!-------CSS Stylesheet------>
	<link rel="stylesheet" href="uwai.css">
	
	<!-------Page Title------>
	<title><?php echo $general->login ?></title>
</head>
<body>

<?php

	
		if(isset($_POST["guest"])) {
		$_SESSION['guest'] = true;
		unset($_SESSION['id']);
		//header("Location: /uwai/home.php");
				echo '<script>
				$.mobile.changePage("home.php",{transition:"flow"});
				</script>';
	}
	?>

	<!-------Header Nav Bar------>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" data-ajax="false"  data-transition="none">

	<div data-role="header" class="header">
	<button type="submit" class="defaultFont" name="prev"><?php echo $general->back ?></button>
		<h1><?php echo $general->login ?></h1>
	</div>
	</form>
	<div class="headerBlock"></div>
	
	<br>
	
	
	<!--Logo-->
	<img src=<?php echo '"Assets/' . $general->images->logo->__toString() . '"'; ?> class="logo">

	<div class="text">
	<!--Login-->
	<?php 	//Check to see if the login button has been pressed
	if(isset($_POST["submit"])) {
		login($_POST["username"], $_POST["password"]);
	}?>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" data-ajax="true"  data-transition="none">
		<div class="form-group">
			<label for="text" class="defaultFont"><?php echo $general->email ?>:</label>
			<input type="text" class="form-control" name="username">
		</div>
		<div class="form-group">
			<label for="password" class="defaultFont"><?php echo $general->accessCode ?>:</label>
			<input type="password" class="form-control" name="password">
		</div>
		<br>
		<button type="submit" class="round widecenter defaultFont" name="submit"><?php echo $general->login ?></button>
	</form>
	
	<!-- Register -->
	<a href="register.php" data-transition="none">
		<button type="submit" class="round widecenter smallerText" name="register"><?php echo $general->registerWithWaterloo ?></button>
	</a>
	
	
	<!-- Login Guest -->
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" data-ajax="true"  data-transition="none">
		<button type="submit" class="round widecenter smallerText" name="guest"><?php echo $general->guest ?></button>
	</form>
	<br>
	</div>
</body>



</html>	
