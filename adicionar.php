<!DOCTYPE html>
<html>
    <head>
        <title>Troca Cromos</title>
        <link rel="stylesheet" href="style.css">
		<meta http-equiv="refresh" content="10">
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
		
		//Seleciona Tudo da Tabela Pokemon
		$query = "SELECT * FROM pokemon";
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
		//Define o $i com 1
    	$i=1;
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

<!-- Texto E Escolher Pokemon e Quantidade no ADICIONAR -->
<div style="margin-top:-430px; margin-left:400px">
	<form action="add.php" class="nomes" method="POST">
		<h1> ADICIONAR POKEMONS </h1>
			<label> Escolha Um Pokemon:</label><br>
				<select name="idpokemon">
    				<?php 
					while( $row = mysqli_fetch_assoc($result)){  
							   echo "<option value='". $row['id_pokemon']."'>" .$row['nome'] ."</option>";   
						} ?>
				</select><br>
			<label> Quantos Quer Adicionar:</label><br>
				
				<select name="quantidade">
    				<?php
					//contador de 1 a 10
    				for($i=1;$i<=10;$i++){ 
        				echo "<option value='". $i ."'>" .$i ."</option>";
    				} ?>
				</select>
		<input style="margin-left: 150px" type="submit" name="submit" value="Adicionar">
	</form>

<?php	
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
    $i=1;
  ?>

<br><br><br><br><br><br>

<!-- Texto E Escolher Pokemon e Quantidade no REMOVER -->
<form action="remove.php" class="nomes" method="POST">
	<h1> REMOVER POKEMONS </h1>
		<label> Escolha Um Pokemon:</label><br>
			<select name="idpokemon">
    			<?php
    			while( $row = mysqli_fetch_assoc($result) )
    			{ 
        			echo "<option value='". $row['id_pokemon']."'>" .$row['nome'] ."</option>";
    			} ?>
			</select><br>
		<label> Quantos Quer Remover:</label><br>
			<select name="quantidade">
    			<?php
				//contador de 1 a 10
    			for($i=1;$i<=10;$i++){ 
        			echo "<option value='". $i ."'>" .$i ."</option>";
    			} ?>
			</select>
		<input style="margin-left: 150px" type="submit" name="submit" value="Remover">
</form>

	<?php
	//mensagens de sucesso e erro
		if ($_SESSION['sucess'] == '1'){
			echo $_SESSION['suc'];  }
		else echo "";
	?>

</div> <!-- div la de cima -->


