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
		//Executa a Página
  		require_once('cookies/header.php');
  		require_once('cookies/configDb.php');

		//conectar a base de dados
		$db = connectDB();
				
		//Sucesso?				
		if ( is_string($db) ){
			//Erro
			echo ("Fatal error! Please return later.");
			die() ;}
		
		//Seleciona Tudo dos Users
		$query = "SELECT * FROM users";
		//Prepara o Statement		
		$statement = mysqli_prepare($db, $query);
		//se statement tiver vazio ERRO a executar
		if (!$statement ){
			//error preparing the statement. This should be regarded as a fatal error.
			echo "Something went wrong . Please try again later.";
			die();  }				
				
		//Executar Statement Preparada para a Variavel Result
		$result = mysqli_stmt_execute($statement);
				
		//se result tiver vazio ERRO a executar
		if( !$result ) {
			//again a fatal error when executing the prepared statement
			echo "Something went very wrong. Please try again later.";
			die();  }

		//Guarda os resultados da tabela Users
		$result = mysqli_stmt_get_result($statement);
				
		//se result tiver vazio ERRO a executar
		if (!$result){
			//again a fatal error: if the result cannot be stored there is no going forward
			echo "Something went wrong. Please try again later.";	
			die();
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

<div style="margin-top:-400px; margin-left:400px">
	<?php
		//------Vai Ao $result de cima e escreve Linha a Linha da Tabela-------------
		while( $row = mysqli_fetch_assoc($result) )
			{
				//verificar se é Uilizador 
			if ($row['admini']==0){
				//e vai definir o $DEFINIR com 1
				$definir = '1' ;  }

				//verificar se é SuperUtilizador 	
			elseif ($row['admini']==2){
				//e vai definir o $DEFINIR com SUPER UTILIZADOR
				$definir = 'Super Utilizador';  }
				//verificar se é ADMIN
			else { 
				//e vai definir o $DEFINIR com 0
				$definir =  '0' ;  }
			
			//Linha a Linha dos Users
			echo 
			'<img alt="Foto de Perfil" style="width:100px; height:100px; padding:10px" src=' .  
			$row['photo'] . ' >' .
			$row['username'] . "            " . 
			$row['email'] . "    " . 
			$row['gender'] ;
			//Apagar Utilizador
			if($definir=='Super Utilizador'){ echo "<br><br>";}
			else{ echo
			'<a href="apagarUtilizador.php?id=' . $row['id_users'] . '"> Apagar</a>' .
			'<form action="modificarUtilizador.php" style="margin-left:120px"  method="POST" name="formModifica">
			<input type="hidden" value="' . $row['id_users'] . '" name="id">
			<input type="submit" name="modificar" value="Modificar Password"> </form>';
			}
			//Ver Perfil
		echo'<form action=profile.php style="margin-left:120px">
			<input name=iduser type=hidden value="'.$row['id_users'].'">
			<span> <input type=submit name=submit value=Visualizar Perfil> </form> </span> <br><br>';
			
			//mostra ao SUPER UTILIZADOR,apenas, o botao para tornar admin ou tornar utilizador 
			if($definir=="Super Utilizador"){
					echo"<div style='margin-top:-130px; margin-left:130px; position:absolute;font-size:20px'> 
					É Super Utilizador </div>";  }
			
			else{
				//se For Utilizador Vai Aparecer um Botao para dar ADMIN
				if($definir==1){  
						echo '<form action="admin.php" style="margin-left:120px; margin-top:-150px; position:absolute;"  method="POST" name="formTroca">
						<input type="hidden"  name="trocar1" value="'.$row['username'].'">
						<label class="visua"">DEFINIR ADMIN
						<input class="visua" type="submit" name="trocar" value="'.$definir.'"> </label> </form>';	}

				//se For ADMIN Vai Aparecer um Botao para remover para utilizador
				else{
						echo '<form action="admin.php" style="margin-left:120px; margin-top:-150px; position:absolute;"  method="POST" name="formTroca">
						<input type="hidden" name="trocar1" value="'.$row['username'].'">
						<label class="visua";">DEFINIR UTILIZADOR
						<input class="visua" type="submit" name="trocar" value="'.$definir.'"> </label> </form>'; }
				}

			echo '</form>';
		}

	if(!isset($_POST['trocar'])){
		echo""; }

	else{
		if(!isset($_POST['trocar'])){
			echo""; }
		else{
			//Vai Guardar Os valores Se o SuperUtilizador Carregar Em algum Botao
			$trocar=$_POST['trocar'];
			$username=$_POST['trocar1'];

			//Vai Dar Update às Permissões se O SUPERUTILIZADOR Selecionar algum Botao
			$query2= "UPDATE users SET admini=$trocar WHERE username='$username'";
			$statement2 = mysqli_prepare($db, $query2);
			$result2 = mysqli_stmt_execute($statement2);

			echo("<meta http-equiv='refresh' content='0'>");

			if($definir=="1"){
				echo "Modificou ".$username." para ".$definir;}
		}
	}
?>



	