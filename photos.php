<!DOCTYPE HTML>
<html>
  <head>
    <title>Welcome</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="style.css">
	<meta http-equiv="refresh" content="1000">
  </head>
  <body> 
	
  <?php
		//include the header div, where authentication is checked and the navigation menu is placed.
  		require_once('cookies/header.php');
  ?>
  
  <?php	
  		require_once('cookies/configDb.php');
			
		//connected to the database
		$db = connectDB();
				
		//success?				
		if ( is_string($db) ){
			//error connecting to the database
			echo ("Fatal error! Please return later.");
			die();
		}
		
		//select all columns from all users in the table
		$query = "SELECT id_pokemon,nome,categoria,quantidade, photo FROM pokemon";
  		
  		//prepare the statement				
		$statement = mysqli_prepare($db, $query);
				
		if (!$statement ){
			//error preparing the statement. This should be regarded as a fatal error.
			echo "Something went wrong . Please try again later.";
			die();				
		}				
				
		//execute the prepared statement
		$result = mysqli_stmt_execute($statement);
							
		if( !$result ) {
			//again a fatal error when executing the prepared statement
			echo "Something went very wrong. Please try again later.";
			die();
		}
				
		//get the result set to further deal with it
		$result = mysqli_stmt_get_result($statement);
				
		if (!$result){
			//again a fatal error: if the result cannot be stored there is no going forward
			echo "Something went wrong. Please try again later.";	
			die();
		}
?>


<?php


while( $row = mysqli_fetch_assoc($result) ){
echo	'<div class="gallery">
		<form action=pokemonpage.php>
		<input type="image" src="'.$row['photo'].$row['nome'].'.png"  name=submit value=Trocar style="width="260" height="260"">
		</a>
		<div class="desc">'.$row['nome'].'</div>
		</div>
		<input name=idpagepokemon type=hidden value="'.$row['id_pokemon'].'">
		</form>'; }
  
  ?>

  </body>
</html>