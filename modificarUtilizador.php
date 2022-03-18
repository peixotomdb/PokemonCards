<!DOCTYPE HTML>
<html>
  <head>
    <title>Update User</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
     <link rel="stylesheet" href="style.css">
  </head>
  <body> 

  <?php
		//include the header div, where authentication is checked and the navigation menu is placed.
  		require_once('cookies/header.php');
  ?>  
  
  <?php
 	
  		//this update will be done by having the user id passed on by POST
  		if ( !empty($_POST)){
 					
			/* If there is an user id available in $_POST, then it is the first time showing this page.  			
			 * Data should be loaded from the database and placed on each field that can be changed. 
			 * There are several ways to do this (all fields, one field at a time, etc).
			 * This example will allow the user to change the email and password, only. 
			*/
			
			if ( count($_POST) == 2 && array_key_exists('id', $_POST) && is_numeric(trim($_POST['id'])) ){
				//id parameter is passed on, which does not mean that it exists on the database. 
				 
				//get it to a local variable
				$id = trim($_POST['id']);

				require_once('cookies/configDb.php');
			
				//connected to the database
				$db = connectDB();
				
				//success?				
				if ( is_string($db) ){
					//error connecting to the database
					echo ("Fatal error! Please return later.");
					die();
				}
			
				//check if username or email already exist - Prepared statement
				$query = "SELECT * FROM users WHERE id_users=?";

				//prepare the statement				
				$statement = mysqli_prepare($db,$query);
				if (!$statement ){
					//error preparing the statement. This should be regarded as a fatal error.
					echo "Something went wrong. Please try again later.";
					die();				
				}				
				
				//now bind the parameters by order of appearance
				$result = mysqli_stmt_bind_param($statement,'i', $id);
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
				elseif( mysqli_num_rows($result) == 1 ){
					//there is a user with this id. Get data to a local variable and it will be printed on the form.
					$user = mysqli_fetch_assoc($result);
				}
				else{
					//there is no user with this id: the request has an error.Place an appropriated error message.
					$_SESSION['code'] = 3;					
					header('Location:pokemon.php');					
					die();
				}
			} //end if to get user data to insert in the form
			elseif( array_key_exists('email', $_POST) && array_key_exists('password', $_POST) && array_key_exists('rpassword', $_POST) && array_key_exists('id', $_POST) ){
				//well, the user may have already changed some data and it must be validated

				//get the user's id from the form's hidden field - If this was not from an admin perspective, the user can only change it's own data. Check id saved in session.
				$id = trim($_POST['id']);
			
	  			//include validation tools
  				require_once('cookies/valida.php');
  		
  				//call general form validation function
  				$errors = validaFormModifica($_POST);
  		
  				//check validation result and act upon it
  				if ( !is_array( $errors) && !is_string($errors) ){
				
					require_once('cookies/configDb.php');
			
					//connected to the database
					$db = connectDB();
				
					//success?				
					if ( is_string($db) ){
						//error connecting to the database
						echo ("Fatal error! Please return later.");
						die();
					}
	
					//building query string
					$email = trim($_POST['email']);			
					$password = md5(trim($_POST['password']));
							
					//check if email already exists - Prepared statement
					$query = "SELECT id_users,email,password FROM users WHERE email=?";

					//prepare the statement				
					$statement = mysqli_prepare($db,$query);	
					if (!$statement ){
						//error preparing the statement. This should be regarded as a fatal error.
						echo "Something went wrong. Please try again later.";
						die();				
					}				
				
					//now bind the parameters by order of appearance
					$result = mysqli_stmt_bind_param($statement,'s',$email);			
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
					elseif( mysqli_num_rows($result) == 0 || (mysqli_fetch_assoc($result))['id_users'] == $id){
						
						/*The email does not exist (this means that it is also different from the one the user has).
						 *Even that the password did not changed, there is no problem in updating the data.
						 */
						$query = "UPDATE users SET email=?, password=? WHERE id_users=?"; 
					
						//prepare the statement				
						$statement = mysqli_prepare($db,$query);
						if (!$statement ){
							//error preparing the statement. This should be regarded as a fatal error.
							echo "Something went wrong. Please try again later.";
							die();				
						}				
				
						//now bind the parameters by order of appearance
						$result = mysqli_stmt_bind_param($statement,'ssi',$email,$password,$id);
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
						else{
					
							//user registered - close db connection
							$result = closeDb($db);
								
							//user information updated
							$_SESSION['code'] = 4;
							header('Location:pokemon.php');
							die();
						}
				}
				else{
					//the returned email does belong to the user that is having it's data updated
					$existingRecords['email'] = true;	
				}
  			}
  			elseif( is_string($errors) ){
				  	//the function has received an invalid argument - this is a programmer error and must be corrected
				  	echo $errors;
				  	
				  	//so that there is no problem when displaying the form
				  	unset($errors);
  			}
  		}
  	}
  	else{
		//this page cannot be loaded without the id data.
		$_SESSION['code'] = 3;
		header('Location:pokemon.php');
		die();  	
  	}
  ?>	  	
	<?php
		//show if there is already either the same username or email in the user table on the database. This code can be placed anywhere the student desires. 
		if ( !empty($existingRecords) ){
			
			if ( $existingRecords['email'] ){
				//the email already exists in the database
				echo "The email already exists in our records.";				
			}			
		}
	?>  
  <form action="" method="POST">
  		
		<label for="email">Email:</label><br>
  		<input type="text" id="email" name="email" value="<?php
  		
  			if ( !empty($errors) && !$errors['email'][0] ){ 
  				echo $_POST['email'];
  			}
  			elseif( !empty($user) ){
  				echo $user['email'];
  			}
  		
  		?>"><br>
  		<?php
  			if ( !empty($errors) && $errors['email'][0] ){
  				echo $errors['email'][1] . "<br>";
  			}  		
  		?>
  		
  		<label for="password">Password:</label><br>
  		<input type="password" id="password" name="password"><br>
  		<?php
  			if ( !empty($errors) && $errors['password'][0] ){
  				echo $errors['password'][1] . "<br>";
  			}  		
  		?>
		
		<label for="rpassword">Repeat Password:</label><br>
  		<input type="password" id="rpassword" name="rpassword"><br>
  		<?php
  			if ( !empty($errors) && $errors['rpassword'][0] ){
  				echo $errors['rpassword'][1] . "<br>";
  			}  		
  		?>
  		
  		<?php
  			//set up an hidden field to sustain the user's id throughout this process.
  			echo '<input type="hidden" name="id" value="' . $id . '"><br>';
  		?>
		<input type="submit" value="Update">
	</form> 
	
  </body>
</html>