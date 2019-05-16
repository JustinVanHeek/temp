 <?php 
	session_start();
	$_SESSION['previousPage'] = array();
	if(!isset($_SESSION['back'])) {
		$_SESSION['back'] = false;
	}
	if(end($_SESSION['previousPage']) != "index.php" AND !($_SESSION['back'])) {
		array_push($_SESSION['previousPage'],"index.php");	
	}
	$_SESSION['back'] = false;
	
	//Auto-Language Login

		if(isset($_COOKIE["lang"])) {
			$_SESSION['lang'] = $_COOKIE["lang"];
				header('Location: login.php');
				
		}
	
	


?>


<!DOCTYPE html>
<html>
<head>

	<!-------jQuery Mobile Header Data------->
		<!-- Include meta tag to ensure proper rendering and touch zooming -->
		<meta name="viewport" content="width=device-width, user-scalable=no" />

		<!-- Include jQuery Mobile stylesheets -->
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

		<!-- Include the jQuery library -->
		<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

		<!-- Include the jQuery Mobile library -->
		<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	
	<!-------CSS Stylesheet------>
	<link rel="stylesheet" href="uwai.css">

	<!-------Page Title------>
	<title>Language Selection</title>
</head>
<body>

<?php
		
	
	//Once language has been chosen, set that language to the session and go to the next page
if (isset($_POST['lang'])) {
	$_SESSION['lang'] = $_POST['lang'];
	setcookie("lang", $_SESSION['lang'], time() + (86400 * 30), "/");
	//header('Location: login.php');
	echo '<script>
		$.mobile.changePage("login.php",{transition:"flow"});
	</script>';
				
}
if (!(isset($_SESSION['lang']))) {
$_SESSION['lang'] = "en";
}
?>

	<!-------Header Nav Bar------>
	<div data-role="header" class="header">
		<h1>Language Selection</h1>
	</div>
		<div class="headerBlock"></div>


	<!--Language Selection-->
	<div>
		<br>
		Please select from the following:<br>
		<br>
		<form action="" method="post" data-ajax="true" data-transition="none">
			<button type="submit" name="lang" value="en"><input type="image" style="margin:5px" class="middle" src="Assets/canada-flag.png" alt="Submit Form" name="lang" value="en"><input type="image" style="margin:5px" class="middle" src="Assets/US-flag.png" alt="Submit Form" name="lang" value="en">  English</button>
		</form>
		<form action="" method="post" data-ajax="true" data-transition="none">
			<button type="submit" name="lang" value="fr"><input type="image" style="margin:5px" class="middle" src="Assets/quebec-flag.png" alt="Submit Form" name="lang" value="fr"><input type="image" style="margin:5px" class="middle" src="Assets/france-flag.png" alt="Submit Form" name="lang" value="fr">  Francais</button>
		</form>
		<form action="" method="post" data-ajax="true" data-transition="none">
			<button type="submit" name="lang" value="zh"><input type="image" style="margin:5px" class="middle" src="Assets/china-flag.png" alt="Submit Form" name="lang" value="zh">  普通话</button>
		</form>
	</div>
	<br>
	<!--Footer-->
	<div class="footer-background" style="border-top: 1px solid #E0E0E0">
	<div class="university-footer">
	<div style="display:inline-block;margin:10px;width:20%;vertical-align:middle;">
	<img style="width:100%" src="Assets/iela.png" alt="IELA Mobile Learning Winner">
	</div>
	<div style="display:inline-block;width:60%;text-align:center;font-size:3.5vw;font-weight:bold">
	2018 Mobile Learning Winner of the International E-Learning Awards
	</div>
	</div>
	</div>

<br>
<br>
<br>
<br>
	
	<!--<a href="/uwai_test/index.php" data-ajax="false">test</a>-->
	
</body>
</html>
