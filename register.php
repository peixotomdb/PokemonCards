<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="style.css">
		<meta http-equiv="refresh" content="1000">
    </head>

    <body>



<?php
	//include the header div, where authentication is checked and the navigation menu is placed.
  	require_once('cookies/header.php');
?>  
  
<?php

 	if ( !empty($_POST)){
		//include validation tools
  		require_once('cookies/valida.php');
		//call general form validation function
  		$errors = validaFormRegisto($_POST);
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
				$username = trim($_POST['username']);
				$email = trim($_POST['email']);					
				$password = md5(trim($_POST['password']));
        		$pais = trim($_POST['pais']);	
				$gender = $_POST['gender'];
				
				//check if username or email already exist - Prepared statement
				$query = "SELECT username,email FROM users WHERE username=? OR email=?";

				//prepare the statement				
				$statement = mysqli_prepare($db,$query);
				
				if (!$statement ){
					//error preparing the statement. This should be regarded as a fatal error.
					echo "Something went wrong. Please try again later.";
					die();				
				}				
				
				//now bind the parameters by order of appearance
				$result = mysqli_stmt_bind_param($statement,'ss',$username,$email); # 'ss' means that both parameters are expected to be strings.
								
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
					echo "Something went wrong.. Please try again later.";	
					die();
				}
				elseif( mysqli_num_rows($result) == 0 ){
			
					$query = "INSERT INTO users (username, email, password, gender, pais) VALUES (?,?,?,?,?)"; 
					
					//prepare the statement				
					$statement = mysqli_prepare($db,$query);
				
					if (!$statement ){
						//error preparing the statement. This should be regarded as a fatal error.
						echo "Something went wrong... Please try again later.";
						die();				
					}				
				
					//now bind the parameters by order of appearance
					$result = mysqli_stmt_bind_param($statement,'sssss',$username,$email,$password,$gender,$pais); # 'sssss' means that all parameters are expected to be strings.
								
					if ( !$result ){
						//error binding the parameters to the prepared statement. This is also a fatal error.
						echo "Something went wrong.... Please try again later.";
						die();
					}
				
					//execute the prepared statement
					$result = mysqli_stmt_execute($statement);
							
					if( !$result ) {
						//again a fatal error when executing the prepared statement
						echo "Something went very wrong..... Please try again later.";
						
					}
					else{
					
						//user registered - close db connection
						$result = closeDb($db);

						echo '<div class="nav">
						<div class="nav-header">
						<div class="nav-title">
							Trocas de Cromos
						</div>
						</div>
						</div>';
						echo "User registered. Login Now";
						echo '<a href="login.php"> Login</a>';
						die();
					}
				}
				else{
					//there already an username or an email in the database matching the imputed data. Which one is it? Or they both exist?
					
					//get all rows returned in the result: one can have a row if there is only the email or username or two rows if both exist in different records
					$existingRecords = array('email' => false, 'username' => false);					
					
					//now do it as you normally did it					
					while( $row = mysqli_fetch_assoc($result) ) {	
				
						if ( $row['username'] == $username ){
							$existingRecords['username'] = true;						
						}
						if( $row['email'] == $email ) {
							$existingRecords['email'] = true;
						}
					}//end while																
				}//end else	
  			}
  			elseif( is_string($errors) ){
				  	//the function has received an invalid argument - this is a programmer error and must be corrected
				  	echo $errors;
				  	
				  	//so that there is no problem when displaying the form
				  	unset($errors);
  			}
  		}
?>	  	
<?php
		//show if there is already either the same username or email in the user table on the database. This code can be placed anywhere the student desires. 
		if ( !empty($existingRecords) ){
			
			if ( $existingRecords['username'] && $existingRecords['email'] ){
				//both the username and the email already exist in the database
				echo "Both username and email already exist in our records.";				
			}
			elseif( $existingRecords['username'] ) {
				//only the username exists (you can erase the written username so that it does not show up in the filled form, but it seams better to keep it so that the user knows what was the input)
				echo "This username is already taken. Please choose another one.";
			}
			else {
				//only the email exists (you can erase the written email so that it does not show up in the filled form, but it seams better to keep it so that the user knows what was the input)
				echo "This email is already taken. Please choose another one.";
			}
		}//end main if
	?>  


        <div class="nav">
            <div class="nav-header">
              <div class="nav-title">
                Trocas de Cromos
              </div>
            </div>
            <div class="nav-links">
              <a href="login.php">Login</a>
              <a href="register.php">Register</a>
            </div>
        </div>

        
        <div class="caixa">
            <div class="registar"> REGISTER </div>
            <form class="itens" action="" method="POST">


            <label for="username">Username:</label><br>
  		<input type="text" id="username" name="username" value="<?php
  		
  			if ( !empty($errors) && !$errors['username'][0] ){ #this is done to keep the value inputted by the user if this field is valid but others are not
  				echo $_POST['username'];
  			}  
  		
  		?>"><br>
  		<?php
  			if ( !empty($errors) && $errors['username'][0] ){ # Equal to "if ( !empty($errors) && $errors['username'][0] == true ){" #presents an error message if this field has invalid content
  				echo $errors['username'][1] . "<br>";
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
		
		<label for="email">Email:</label><br>
  		<input type="text" id="email" name="email" value="<?php
  		
  			if ( !empty($errors) && !$errors['email'][0] ){ 
  				echo $_POST['email'];
  			}  
  		
  		?>"><br>
  		<?php
  			if ( !empty($errors) && $errors['email'][0] ){
  				echo $errors['email'][1] . "<br>";
  			}  		
  		?>
		
		
		<input type="radio" id="male" name="gender" value="male" <?php	
  			if ( !empty($errors) && !$errors['gender'][0] && $_POST['gender'] == "male"){ 
  				echo "checked";
  			}  
  		?>>
  		<label for="male">Male</label><br>
  		
  		<input type="radio" id="female" name="gender" value="female" <?php	
  			if ( !empty($errors) && !$errors['gender'][0] && $_POST['gender'] == "female"){ 
  				echo "checked";
  			}  
  		?>>
 		<label for="female">Female</label><br>
 		
 		<?php
  			if ( !empty($errors) && $errors['gender'][0] ){
  				echo $errors['gender'][1] . "<br>";
  			}  		
  		?>
 
 <label for="pais">País:</label><br>
 <select id="pais" name="pais">
    <?php
      include 'cookies/country.php';
      foreach($countries as $pais) {   ?>
        <option type="radio" name ="pais" value="<?= $pais ?>" title="<?= htmlspecialchars($pais)?>"> <?= htmlspecialchars($pais) ?> </option>
        <?php }  ?>
      </select> 
      <?php
  			if ( !empty($errors) && !$errors['pais'][0] ){ 
  				echo $_POST['pais'];
  			}  ?>
    
      <br>

            <input style="margin-left: 150px" type="submit" value="Submit">
            </form>
        </div>


