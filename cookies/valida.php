<?php
			//this function's aim is to validate all fields that make up the users' data update form
			function validaFormModifica( $dados ){
				
				//include the web application configuration file to have boundaries to be able to validate fields
				require_once('config.php');
				$errors = array('password' => array(false, "<div style=color:red> Invalid password: it must have between $minPassword and $maxPassword chars and special chars.</div>"),
								    'rpassword' => array(false, "<div style=color:red> Passwords mismatch. </div>"),
								    'email' => array(false,'"<div style=color:red> Invalid email.</div>')
								   );
				$flag = false;
				
				/* Check if the imputed data is according what is expected for this function
				 * In short, $dados must have at least the necessary fields to enable their validation.
				 */			
				if ( !is_array($dados) || count (array_intersect_key(array_keys($errors), array_keys($dados) ) ) < 3 ){
					return("This function needs a parameter with the following format: array(\"password\"=> \"value\", \"rpassword\"=> \"value\", \"email\"=> \"value\"");
					die();
				}	

				$dados['password'] = trim($dados['password']);
				$dados['rpassword'] = trim($dados['rpassword']);
				$dados['email'] = trim($dados['email']);		
			
				//check password
				if( !validatePassword($dados['password'], $minPassword, $maxPassword) ){
					$errors['password'][0] = true;
					$flag = true;				
				}
				elseif( $dados['rpassword'] != $dados['password']){
					$errors['rpassword'][0] = true;
					$flag = true;
				}
				
				if( !validateEmail($dados['email'])){
					$errors['email'][0] = true;
					$flag = true;				
				}
				
				//deal with the validation results
				if ( $flag == true ){
					//there are fields with invalid contents: return the errors array
					return($errors);
				}
				else{
					//all fields have valid contents
					return(true);				
				}
			}			
			
			//this function's aim is to validate all fields that make up the login form
			function validaFormLogin( $dados ){
				
				//include the web application configuration file to have boundaries to be able to validate fields
				require_once('config.php');
				
				$errors = array('username' => array(false, "<div style=color:red> Invalid username: it must have between $minUsername and $maxUsername chars.</div>"),
								    'password' => array(false, "<div style=color:red> Invalid password: it must have between $minPassword and $maxPassword chars and special chars.</div>"),
								    );	
				
				$flag = false;		
						
				/* Check if the imputed data is according what is expected for this function
				 * In short, $dados must have at least the necessary fields to enable their validation.
				 */			
				if ( !is_array($dados) || count (array_intersect_key(array_keys($errors), array_keys($dados) ) ) < 2 ){
					return("This function needs a parameter with the following format: array(\"username\"=> \"value\", \"password\"=> \"value\")");
					die();
				}			
			
				$dados['username'] = trim($dados['username']);
				$dados['password'] = trim($dados['password']);
					
				//validate username
				if( !validateUsername($dados['username'], $minUsername, $maxUsername) ){
					$errors['username'][0] = true;
					$flag = true;				
				}
			
				if( !validatePassword($dados['password'], $minPassword, $maxPassword) ){
					$errors['password'][0] = true;
					$flag = true;				
				}
				
				//deal with the validation results
				if ( $flag == true ){
					//there are fields with invalid contents: return the errors array
					return($errors);
				}
				else{
					//all fields have valid contents
					return(true);				
				}
			}
			
			//this function's aim is to validate all fields that make up the register form
			function validaFormRegisto( $dados ){
				
				//include the web application configuration file to have boundaries to be able to validate fields
				require_once('config.php');
		
				$errors = array('username' => array(false, "<div style=color:red> Invalid username: it must have between $minUsername and $maxUsername chars.</div>"),
								    'password' => array(false, "<div style=color:red> Invalid password: it must have between $minPassword and $maxPassword chars and special chars.</div>"),
								    'rpassword' => array(false, "<div style=color:red> Passwords mismatch.</div>"),
								    'email' => array(false,"<div style=color:red> Invalid email.</div>"),
								    'gender' => array(false,"<div style=color:red> Please select a gender.</div>"),
									'pais' => array(false,"<div style=color:red> Invalid Country</div>"),
								   );

				$flag = false;
				
				/* Check if the imputed data is according what is expected for this function
				 * In short, $dados must have at least the necessary fields to enable their validation.
				 */			
				if ( !is_array($dados) || count (array_intersect_key(array_keys($errors), array_keys($dados) ) ) < 4 ){
					return("This function needs a parameter with the following format: array(\"username\"=> \"value\", \"password\"=> \"value\", \"rpassword\"=> \"value\", \"email\"=> \"value\", [optional] \"gender\"=> \"value\", [optional] \"pais\"=> \"value\"\"=> \"value\")");
					die();
				}	

				$dados['username'] = trim($dados['username']);
				$dados['password'] = trim($dados['password']);
				$dados['rpassword'] = trim($dados['rpassword']);
				$dados['email'] = trim($dados['email']);	
			
				//validate username
				if( !validateUsername($dados['username'], $minUsername, $maxUsername) ){
					$errors['username'][0] = true;
					$flag = true;				
				}
			
				//check if gender is selected
				if ( !array_key_exists('gender', $dados)){
					$errors['gender'][0] = true;
					$flag = true;
				}  		
				//check if pais is selected
				if ( !array_key_exists('pais', $dados)){
					$errors['pais'][0] = true;
					$flag = true;
				}  						
				
				//check password
				if( !validatePassword($dados['password'], $minPassword, $maxPassword) ){
					$errors['password'][0] = true;
					$flag = true;				
				}
				elseif( $dados['rpassword'] != $dados['password']){
					$errors['rpassword'][0] = true;
					$flag = true;
				}
				
				if( !validateEmail($dados['email'])){
					$errors['email'][0] = true;
					$flag = true;				
				}
				
				//deal with the validation results
				if ( $flag == true ){
					//there are fields with invalid contents: return the errors array
					return($errors);
				}
				else{
					//all fields have valid contents
					return(true);				
				}
			}			
			
			
			/*---------------------------------------------------------------------------------------------------------------------------------------------
			 * Validation functions
			 */ 
	
			function validateUsername($username, $min, $max){
				
				$exp = "/^[A-z0-9_]{" . $min . "," . $max .'}$/';			
										
				if( !preg_match($exp, $username )){
					return (false);				
				}else {
					return(true);
				}
			}

			function validatePassword($data, $min, $max){
				
				$exp = "/^[A-z0-9_\\\*\-]{" . $min . "," . $max .'}$/';			
					
				if( !preg_match($exp, $data)){
					return (false);				
				}else {
					return(true);
				}
			}

			function validateEmail($email){
				
				//remove unwanted chars that maybe included in the email field content
				$email = filter_var($email, FILTER_SANITIZE_EMAIL);
				
				//verify if the inputted email is according to validation standards
				if( !filter_var($email, FILTER_VALIDATE_EMAIL)){
					return (false);				
				}else {
					return(true);
				}
			}


?>