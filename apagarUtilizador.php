<?php

//check if the GET request is according to what is expected in this script
if ( !empty($_GET) && array_key_exists('id', $_GET) && is_numeric(trim($_GET['id'])) ){
	
	//save the id in a local variable
	$id = trim($_GET['id']);
	
	//connect to the database
	require_once('cookies/configDb.php');
	$db = connectDB();
				
	//success?				
	if ( is_string($db) ){
		//error connecting to the database
		echo ("Fatal error! Please return later.");
		die();
	}
				
	//construct the intend query
	$query = "SELECT username FROM users WHERE id_users=?";
				
	//prepare the statement				
	$statement = mysqli_prepare($db,$query);
				
	if (!$statement ){
		//error preparing the statement. This should be regarded as a fatal error.
		echo "Something went wrong. Please try again later.";
		die();				
	}				
							
	//now bind the parameters by order of appearance
	$result = mysqli_stmt_bind_param($statement,'i',$id); 
	
	if ( !$result ){
		//error binding the parameters to the prepared statement. This is also a fatal error.
		echo "Something went wrong. Please try again later.";
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
	elseif( mysqli_num_rows($result) == 1 ) {
		//there is a user with this id
	
		//get user data
		$row = mysqli_fetch_assoc($result);
		
		session_start();
		if( $row['username'] == $_SESSION['username'] ) {
			//we are trying to erase the authenticated user
			
			//Close db connection
			$result = closeDb($db);
			
			header('Location:admin.php');
			die();
		}
		else{
			//we can now proceed and erase the user
			
			//construct the intend query
			$query = "DELETE FROM users WHERE id_users=?";
				
			//prepare the statement				
			$statement = mysqli_prepare($db,$query);
				
			if (!$statement ){
				//error preparing the statement. This should be regarded as a fatal error.
				echo "Something went wrong. Please try again later.";
				die();				
			}				
							
			//now bind the parameters by order of appearance
			$result = mysqli_stmt_bind_param($statement,'i',$id); 
	
			if ( !$result ){
				//error binding the parameters to the prepared statement. This is also a fatal error.
				echo "Something went wrong. Please try again later.";
				die();
			}
				
			//execute the prepared statement
			$result = mysqli_stmt_execute($statement);
							
			if( !$result ) {
				//again a fatal error when executing the prepared statement
				echo "Something went very wrong. Please try again later.";
				die();
			}
			
			//success! Close db connection
			$result = closeDb($db);
					
			//send the user to another page
			header('Location:admin.php');
		}
	}
	else{
		//Close db connection
		$result = closeDb($db);
		
		//there is no user it that id	
		header('Location:admin.php');	
		die();
	}
}
else{
	header('Location:admin.php');
	die();
}
?>