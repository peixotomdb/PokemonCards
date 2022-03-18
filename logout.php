<!DOCTYPE HTML>
<html>
  <head>
    <title>Logout</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="css/myCSS.css">
    <meta http-equiv="refresh" content="1000">
  </head>
  <body> 
    
  <?php
		//include the header div, where authentication is checked and the navigation menu is placed.
  		require_once('cookies/header.php');
  ?>
	  
  <?php
  		unset($_SESSION["username"]);
		
		//empty session array
		$_SESSION = array();
		
		//create a code for successful logout
		$_SESSION['code'] = 2;
		
		//send the user to the bye bye page.
		header("Location:login.php");
  ?>
	
  </body>
</html>