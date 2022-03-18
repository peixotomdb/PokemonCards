<!DOCTYPE html>
<html>
    <head>
        <title>Troca Cromos</title>
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
  			$errors = validaFormLogin($_POST);
  			//check validation result and act upon it
  			if ( !is_array( $errors) && !is_string( $errors) ){
				$errors=array();

				
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
				$password = md5(trim($_POST['password']));

				//construct the intend query
				$query = "SELECT * FROM users WHERE username=? AND password=?";
				
				//prepare the statement				
				$statement = mysqli_prepare($db,$query);
				
				if (!$statement ){
					//error preparing the statement. This should be regarded as a fatal error.
					echo "Something went wrong. Please try again later.";
					die();				
				}				
								
				//now bind the parameters by order of appearance
				$result = mysqli_stmt_bind_param($statement,'ss',$username,$password); # 'ss' means that both parameters are expected to be strings.
								
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
				elseif( mysqli_num_rows($result) == 1){
					//there is one user only with these credentials
					$errors=array();
					//open session
					session_start();
					
					//get user data
					$user = mysqli_fetch_assoc($result);
					
					//save username and id in session					
					$_SESSION['username'] = $user['username'];
					$_SESSION['id'] = $user['id_users'];
					$_SESSION['email'] = $user['email'];
					$_SESSION['pais'] = $user['pais'];
					$_SESSION['photo'] = $user['photo'];
					$_SESSION['admini'] = $user['admini'];
				
					//user registered - close db connection
					$result = closeDb($db);
					
					//send the user to another page
					header('Location:pokemon.php');
				}
				else{
					echo "<div style=color:red>Invalid Username/Password</div>";
					$result = closeDb($db);
				}	
  			}
			
  			elseif( is_string($errors) ){
				  	//the function has received an invalid argument - this is a programmer error and must be corrected
					  $errors=array();
					echo $errors;

				  	//so that there is no problem when displaying the form
				  	unset($errors);
  			}
  		}
  ?>






        <div class="nav">
            <div class="nav-header">
              <div class="nav-title">
                Trocas de Cromos
              </div>
            </div>
            <div class="nav-links">
              <a href="loginpage.php">Login</a>
              <a href="register.php">Register</a>
            </div>
        </div>
        
        <div class="bloco">

            <div class="text_bloco">Login</div>
            <form action="" method="POST">
  		<label for="username">Username:</label><br>
  		<input type="text" id="username" name="username" value="<?php
  		
  			if ( !empty($errors) && !$errors['username'][0] ){ #this is done to keep the value inputted by the user if this field is valid but others are not
  				echo $_POST['username'];
  			}  
  		
  		?>"><br>
  		<?php
  			if ( !empty($errors) && $errors['username'][0] ){
  				echo $errors['username'][1] . "<br>";
  			}  		
  		?>
            <br>

            <label for="password">Password:</label><br>
  		<input type="password" id="password" name="password"><br>
  		<?php
			$errors=array();
  			if ( !empty($errors) && $errors['password'][0] ){
  				echo $errors['password'][1] . "<br>";
				  $_SESSION = array();  }  		 
  		?>
      <br>
		
		<input type="submit" value="Submit">
	</form>

    <div class="text_inside"> É novo? <br> </div>
        <a href="register.php">
        	<div  href class="registarb">Registar Aqui </div>
    	</a>
    </div>

    <div class="titulo"> Trocas de Cromos </div>
        <div class="texto"> Bem-vindo ao melhor site de cromos do pokemon!
							Através da nossa plataforma vais poder trocar cromos dos mais raros aos mais comuns com outros utilizadores de todas as partes do mundo
 							de maneira a completares a tua preciosa caderneta.
							Do que estas à espera ? Regista-te já e vêm viver esta experiência connosco! </div>
        <div class="revistas"> Revistas </div>

        <a href="pokemon.php"><img src="img/capa.png" class="capa" alt="Revista"> </a>
        
		<img src="img/breve.png" class="breve" alt="Em breve">


              
    <footer class="site-footer">
            <div class="footertext">
              <h6>Sobre</h6>
              <p class="text-justify">A caderneta de cromos é um site fundado por três alunos de comunicação e multimédia que tem como conceito o poder de trocar cromos 
				do pokemon com outros utilizadores de todas as partes do mundo.</p>
            </div>
  </footer>


    </body>


    