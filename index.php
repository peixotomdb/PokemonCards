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
										
					//open session
					session_start();
					
					//get user data
					$user = mysqli_fetch_assoc($result);
					
					//save username and id in session					
					$_SESSION['username'] = $user['username'];
					$_SESSION['id'] = $user['id_users'];
				
					//user registered - close db connection
					$result = closeDb($db);
					
					//send the user to another page
					header('Location:pokemon.php');
				}
				else{
					echo "Invalid Username/Password";
					$result = closeDb($db);
				}	
  			}
  			elseif( is_string($errors) ){
				  	//the function has received an invalid argument - this is a programmer error and must be corrected
				  	echo $errors;
				  	
				  	//so that there is no problem when displaying the form
				  	unset($errors);
  			}
  		}
  ?>


<!--//////////////////////////////////////BLOCO PERFIL///////////////////////////////////////////////-->
<div class="bloco">
<!------------------FOTO------------------------------->
<img alt="Foto de Perfil" class="profpic" src="<?php echo($_SESSION['photo']) ?>">

<!------------------hover para trocar------------------>
<label for="fileupload" >
<img src="img/change.png" alt=" mudar foto" class="mudar">
</label>

<!------------------selecionar foto ------------------->
<form  method="post" action="upload.php" enctype="multipart/form-data">
<input id="fileupload" type="file" name="myFile" />

<!------------------TEXTOS  --------------------------->
<!-----Se for 0 escreve Utilizador,, 1 escreve ADMIN,, 2 escreve SUPER UTILIZADOR-->
<?php if( ($_SESSION['admini'])==0){  echo '<p class="texto444"> Utilizador </p>'; }
if( ($_SESSION['admini'])==1){  echo '<p class="texto444"> Admin </p>'; };
if( ($_SESSION['admini'])==2){  echo '<p class="texto444"> S.U </p>'; } ?>
<!---------Verifica Quem esá Na sessao e escreve o nome, o pais e o email----------------->
<p class="texto555">	<?php echo($_SESSION['username']); ?></p>
<p class="texto666">	<?php echo($_SESSION['pais']); ?></p>
<p class="texto777">	<?php echo($_SESSION['email']); ?></p>

<!------------------botao para upload ----------------->
<label class="uploadbtn" for="fileupload" >
<input id="uploadbtn" type="submit" value="Upload"><br>
</form> </div>
<!------------------Botao para Ver Perfil ----------------->
<div style="margin-left:30px; position:absolute; margin-top:-140px">
<form action="myprofile.php">
    <button type="submit">Meu Perfil</button>
</form>
<!------------------Botao para Modificar Pass----------------->
<?php echo '<form action="modificarUtilizador.php"  method="POST" name="formModifica">
	<input type="hidden" value="' . $_SESSION['id'] . '" name="id">
	<input type="submit" name="modificar" value="Modificar Password"> </form>'; ?>
</div>
<!--//////////////////////////////////ACABA O BLOCO PERFIL//////////////////////////////////////////////-->
		

	<div class="titulo"> Trocas de Cromos </div>
        <div class="texto"> Bem-vindo ao melhor site de cromos do pokemon!
							Através da nossa plataforma vais poder trocar cromos dos mais raros aos mais comuns com outros utilizadores de todas as partes do mundo
 							de maneira a completares a tua preciosa caderneta.
							Do que estas à espera ? Regista-te já e vêm viver esta experiência comnosco! </div>
    	<div class="revistas"> Revistas </div>

    <a href="pokemon.php">
		<img src="img/capa.png" class="capa" alt="Revista"> 
	</a>

    <img src="img/breve.png" class="breve" alt="Em breve">

           



              
<footer class="site-footer">
    <div class="footertext">
        <h6>About</h6>
            <p class="text-justify">Scanfcode.com <i>CODE WANTS TO BE SIMPLE </i> is an initiative  to help the upcoming programmers with the code. Scanfcode focuses on providing the most efficient code or snippets as the code wants to be simple. We will help programmers build up concepts in different programming languages that include C, C++, Java, HTML, CSS, Bootstrap, JavaScript, PHP, Android, SQL and Algorithm.</p>
    </div>
</footer>


</body>


    