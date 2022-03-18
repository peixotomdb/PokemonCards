<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon</title>
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
		
		//Seleciona Tudo da Tabela User
		$query = "SELECT * FROM users";
  		//Prepara o Statement		
		$statement = mysqli_prepare($db, $query);
		//se statement tiver vazio ERRO a executar
		if (!$statement ){
			//error preparing the statement. This should be regarded as a fatal error.
			echo "Something went wrong . Please try again later.";
			die();				
		}				
				
		//Executar Statement Preparada para a Variavel Result
		$result = mysqli_stmt_execute($statement);
				
		//se result tiver vazio ERRO a executar
		if( !$result ) {
			//again a fatal error when executing the prepared statement
			echo "Something went very wrong. Please try again later.";
			die();
		}

		//Guarda os resultados da tabela Users
		$result = mysqli_stmt_get_result($statement);
				
		//se result tiver vazio ERRO a executar
		if (!$result){
			//again a fatal error: if the result cannot be stored there is no going forward
			echo "Something went wrong. Please try again later.";	
			die();
		}

		//define variaveis para 0, para nao entrar em conflito com valores antigos
		$_SESSION['sucess']=0;
		$_SESSION['suc']=0;
		$_SESSION['comentario']="";
		$sort_option = "";
		//se a variavel sort_option estiver vazia
		if(isset($sort_option))
		//define com id_pokemon para Organizar a Tabela pelos ids do pokemon
		{ $sort_option='id_pokemon'; }
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

<!------------------------------Revista e Informaçoes----------------->
        <img src="img/capa.png" class="capa1" alt="Revista">
		<div class="nomerevista">Pokemon edição 123</div>
        <div class="descrevista"><br><br>Primeira Edição<br>Ano:1999</div>
		<br><br><br>
		<a style="text-decoration: none; width: 10px; margin-left:770px; margin-top:400px;" href="adicionar.php"> 
		<button class="button-73">ADICIONAR CROMOS</button> </a>



<!--////////////////////////////////// TABELA /////////////////////////////////////////////-->

        <div id="lista" class="titulolista">Lista Cromos</div>
<!------------------------------BOTOES PARA ADICIONAR E VER GALERIA----------------->
		<a style="text-decoration: none;margin-left:300px" href="adicionar.php"> <button class="button-73">ADICIONAR CROMOS</button> </a>
		<a style="text-decoration: none;margin-left:220px"href="photos.php">    <button class="button-74"> GALERIA CROMOS </button> </a>
		
<!------------------------------TABELA----------------->
	  	<table stle="margin-left:400px" class="tabela">
		<tr>
		<form action="#lista" method="POST">
		<th>Número <input type="submit" name="idasc" value="▲"/><input type="submit" name="iddesc" value="▼" /> </th></th>
		<th>Nome <input type="submit" name="nome1" value="▲"/><input type="submit" name="nome2" value="▼" /> </th></th>
		<th>Categoria <input type="submit" name="casc" value="▲"/><input type="submit" name="cdesc" value="▼" /> </th></th>
		<th>Quantidade <input type="submit" name="asc" value="▲"/><input type="submit" name="desc" value="▼" /> </th>
		<th>Trocar</th></tr>
	</form>

<!--------------------------------- ORDENAR A TABELA ------------------------------------------->
		<?php
		if(!isset($_POST['idasc'])){echo"";} else{ $sort_option="id_pokemon ASC";}
		if(!isset($_POST['iddesc'])){echo"";} else{ $sort_option="id_pokemon DESC ";}

		if(!isset($_POST['nome1'])){echo"";} else{ $sort_option="nome ASC";}
		if(!isset($_POST['nome2'])){echo"";} else{ $sort_option="nome DESC ";}

		if(!isset($_POST['casc'])){echo"";} else{ $sort_option="categoria ASC";}
		if(!isset($_POST['cdesc'])){echo"";} else{ $sort_option="categoria DESC ";}

		if(!isset($_POST['asc'])){echo"";} else{ $sort_option="quantidade ASC";}
		if(!isset($_POST['desc'])){echo"";} else{ $sort_option="quantidade DESC";}

//Seleciona Tudo da Tabela Pokemon e Ordena com Base no Que 
//   está Dentro a variavel SORT OPTION que foi defenida em cima
	$query1 = "SELECT * FROM pokemon ORDER BY $sort_option";		
	  $statement1 = mysqli_prepare($db, $query1);
	  $result1 = mysqli_stmt_execute($statement1);
	  $result1 = mysqli_stmt_get_result($statement1);
	  
	  //------------- Escreve Todas as Linhas Que Estão Dentro da Tabela Pokemon
	  // OS ROWS ESCREVEM LINHA A LINHA [do que esta aqui dentro]
		while( $row = mysqli_fetch_assoc($result1) )
		{
		echo   
			'<tr><td>'.
			'<a style="text-decoration: none; color:black" >';

		echo '<form name="favorito" action="list.php" method="GET" class="favorito">
			<label>
			<input style="margin-left:-1110px;margin-top:10px; position:absolute" type="image" name="favorito" value="'.$row['id_pokemon'].'" />
			<span style="margin-left:-130px;margin-top:10px; position:absolute;" class="icon">❤</span>
			</label> </form>'.


			$row['id_pokemon'].'</a></td><td>' . '<a style="text-decoration: none; color:black">'.			
			$row['nome'] . '</a></td><td>' . '<a style="text-decoration: none; color:black">'.
			$row['categoria'] . '</a></td><td>' . '<a style="text-decoration: none; color:black">'.			
			$row['quantidade'].'</a></td>

			<td><form action=pokemonpage.php>
			<input name=idpagepokemon type=hidden value="'.$row['id_pokemon'].'">
			<span><input type=submit class="button-75" name=submit value=Trocar></span>
			<img style="margin-left:200px; position:absolute; opacity:0.5" class="img1" src="'.$row['photo'].$row['nome'].'.png""/><img class="img1" src="'.$row['photo'].$row['nome'].'.png""/>
			</form></td>
			</td></tr>';

		}	
    	echo'</table>';
		
		//Se a Variavel SESSION ERROR 1 e 2 NÃO estiver vazia dá ECHO
		if(!isset($_SESSION['error1'])){ echo""; }
		else{ echo $_SESSION['error1']; }
		if(!isset($_SESSION['error2'])){ echo"";}
    	else{ echo$_SESSION['error2']; }  ?>


<!----------------------------- COMENTARIOS ----------------->
<form action="comment.php" method="POST">
	<h1 class="h1com"> Caixa de Comentários </h1>
	<input name="comment" type="text" class="comment-box" placeholder="Deixe Aqui a Sua Opinião">
	<input class="comment-post" type="submit" name="submit" value=" Enviar">
</form>


<?php 
//-------------- Escreve se o Comnetário foi adicionado com Sucesso
	$comentario=$_SESSION['comentario'];
	if ($comentario==1){echo "A sua Mensagem Foi Enviada";}
	else{echo"";}
?>

<!------------------------------FOOTER----------------->
<footer class="site-footer">
            <div class="footertext">
              <h6>Sobre</h6>
              <p class="text-justify">A caderneta de cromos é um site fundado por três alunos de comunicação e multimédia que tem como conceito o poder de trocar cromos 
				do pokemon com outros utilizadores de todas as partes do mundo.</p>
            </div>
</footer>
</body>