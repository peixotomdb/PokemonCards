<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon</title>
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
		$query = "SELECT username, admini , email , pais, photo FROM users";
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
		$_SESSION['sucess']=0;
		$_SESSION['suc']=0

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
  		require_once('cookies/configDb.php');
			
		//connected to the database
		$db = connectDB();
		
		$idpokemon=$_GET['idpagepokemon'];
		if (!$idpokemon){
			header('Location:pokemon.php');
		}

		//select all columns from all users in the table
		$query = "SELECT add_id, add_user, nomeuser, add_quantidade, add_pokemon FROM adicionar WHERE add_pokemon='$idpokemon'";
  		
  		//prepare the statement				
		$statement = mysqli_prepare($db, $query);
		$result = mysqli_stmt_execute($statement);
		$result = mysqli_stmt_get_result($statement);



	//select all columns from all users in the table
	$query1 = "SELECT id_pokemon, nome, categoria, quantidade, photo FROM pokemon WHERE id_pokemon='$idpokemon'";
  		
	//prepare the statement				
	$statement1 = mysqli_prepare($db, $query1);	
	$result1 = mysqli_stmt_execute($statement1);							  
	$result1 = mysqli_stmt_get_result($statement1);
	while( $row = mysqli_fetch_assoc($result1) ){
		{ 
			$nomepokemon=$row['nome'];
			$categoriapokemon=$row['categoria'];
			$photopokemon=$row['photo'];
		}
	}

?>


</div>
        <img src="<?php echo $photopokemon.$nomepokemon.'.png'?> " class="capa1" alt="Revista"> 
        <div class="nomerevista"><?php echo $nomepokemon ?></div>
        <div class="descrevista"><br><?php echo 'Categoria:'. $categoriapokemon ?><br><br>Primeira Edição<br>Ano:1999</div>
        <div class="titulolista">Quem Tem</div>
        </div>





        <?php
		'<div style="text-decoration: none;">';
        echo '<table class="tabela">
		<tr>
		<th>Trocar</th>
		<th>Quem Adicionou</th>
		<th>Quantidade</th></tr>' ;
		while( $row = mysqli_fetch_assoc($result) )
		{
      		echo 
			'<tr><td>
			<form action=profile.php>
			<input name=iduser type=hidden value="'.$row['add_user'].'">
			<span> <input type=submit class="visua" name=submit value=Visualizar> </span>
			</form>
			</td></td><td>' .
			
			$row['nomeuser']						. '</td><td>' . 
			$row['add_quantidade']  . '</td>
			</td></tr>';
		}	
    	echo'</table></div>';  ?>  


              
<footer class="site-footer">
            <div class="footertext">
              <h6>Sobre</h6>
              <p class="text-justify">A caderneta de cromos é um site fundado por três alunos de comunicação e multimédia que tem como conceito o poder de trocar cromos 
				do pokemon com outros utilizadores de todas as partes do mundo.</p>
            </div>
  </footer>


    </body>