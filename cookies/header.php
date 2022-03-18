<?php
session_start();

//there are different actions for the login and register users scripts only
$currentScript = basename($_SERVER['PHP_SELF'], '.php');


if ($currentScript == "admin" ||$currentScript == "comments"){
	if(($_SESSION['admini'])==0) {

		$_SESSION['code'] = 1;
		header('Location:login.php');
		die();
	}
}



//is this the login script?
if ( $currentScript == "login" ||$currentScript == "loginpage"  ||$currentScript == "register"){

	
	//is the user authenticated?
	if ( !empty ($_SESSION) && array_key_exists("username", $_SESSION) ){
		
		//clear any existing status codes
		unset ($_SESSION['code']);
		
		//it must be redirect to the welcome page	
		header('Location:pokemon.php');
		die();			
	}
	elseif(!empty($_SESSION) && array_key_exists("code", $_SESSION)){
		
		//it is not, but there is an error message to show
	 	require_once('errorCodes.php');
	 	echo getErrorMessage($_SESSION['code']) . "<br>";
		
		//clear any existing status codes
		unset ($_SESSION['code']); 	
	}
		
}
else{
	//it is any other script

	//is the user authenticated?	
	if( empty($_SESSION) || !array_key_exists("username", $_SESSION) ){
				
		//It is not! Set up the proper error message and send the user to the login page
		$_SESSION['code'] = 1;
		
		//login.php must be able to deal with errors to present the user with the appropriated error message
		header('Location:login.php');
		die();
	}
	else{
		//it is! Present the navigation bar	
		echo '<div class="nav">
            <div class="nav-header">
              <div class="nav-title">
			  <a style="text-decoration:none ;color:white" href="index.php">Troca de Cromos</a>
              </div>
            </div>
            <div class="nav-links">

			<form style="position:absolute; padding-left:100px; margin-left:-800px" action="search.php" method="POST">
			<input style="width:400px;height:20px; margin-top:10px"type="text" name="search" placeholder="Procurar Utilizadores/Pokemons" value=""><br>
			<input style="position:absolute;margin-top:-20px; margin-left:420px"type ="submit">  </form> ';

				if ( ($_SESSION['admini'])==1) {
				echo '<a href="admin.php">Admin</a> '.
				'<a href="comments.php">Comentários</a> '; }

				if ( ($_SESSION['admini'])==2) {
					echo '<a href="admin.php">Admin</a> '.
					'<a href="comments.php">Comentários</a> '; }

			echo 
			'<a href="myprofile.php" >Perfil</a>
			<a href="list.php" >Lista</a>
            <a href="pokemon.php" >Pokemon</a>
            <a href="logout.php" >Logout</a>
            </div>
        </div>';
			}

	
		
		if( !empty($_SESSION) && array_key_exists("code", $_SESSION) ) {
	 		
			//if it is code 1 it must be cleared as the user is authenticated
			if ($_SESSION['code'] == 1){
				unset($_SESSION['code']);			
			}
			else{	 		
	 			//the user was sent here with an error code. Show the proper error message
	 			require_once('errorCodes.php');
	 			echo getErrorMessage($_SESSION['code']) . "<br>";

				//clear all error codes before proceeding	 			
	 			unset($_SESSION['code']);
	 		}
		}	 
	}
