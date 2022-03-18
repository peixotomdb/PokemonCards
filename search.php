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
		$query = "SELECT id_users,username,admini, email,gender, photo FROM users";
  		
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
			die(); }
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


<?php
		//Verificações na barra de pesquisa
		if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['search']))
			{
				echo "ERROR";
				header('Location:pokemon.php');
				die(); }

		else{
			$search =$_POST['search'];


			if(!$search){
				echo "ERROR";
				header('Location:pokemon.php');
				die(); }  
				
			else{ 
				$sql = "SELECT * FROM users WHERE username like '%$search%'";
				$statement = mysqli_prepare($db, $sql);
				$result = mysqli_stmt_execute($statement);
				$result = mysqli_stmt_get_result($statement);	
				echo '<h2 style="margin-top:50px; margin-left:20px">USERS</h2>';

					while( $row = mysqli_fetch_assoc($result) ){
						echo'
						<div class="searching">
						<form action=profile.php>
						<input type="image" src="'.$row['photo'].'"  name=iduser value=Trocar style="width="183" height="183">
						</a>
						<div class="desc1" style="width="260" height="260">'.$row['username'].'</div>
						</div>
						<input name=iduser type=hidden value="'.$row['id_users'].'">
						</form>'; }

				echo '<h2 style="margin-top:300px; margin-left:20px">POKEMONS</h2>';


				$sql2 = "SELECT * FROM pokemon WHERE nome like '%$search%'";
				$statement2 = mysqli_prepare($db, $sql2);
				$result2 = mysqli_stmt_execute($statement2);
				$result2 = mysqli_stmt_get_result($statement2);	
				while( $row = mysqli_fetch_assoc($result2) ){
					echo
					'<div style="" class="searching">
					<form action=pokemonpage.php>
					<input type="image" src="'.$row['photo'].$row['nome'].'.png""  name=iduser value=Trocar style="width="250" height="250">
					</a>
					<div class="desc1" style="width="260" height="260">'.$row['nome'].'</div>
					</div>
					<input name=idpagepokemon type=hidden value="'.$row['id_pokemon'].'">
					</form>'; }

				}	
			}
		
		