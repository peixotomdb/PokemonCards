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

<div style="margin-top:-500px; margin-left:100px">

  <?php
    $sql = "SELECT id_comments, id_user, user_name, comment, day FROM comments";
    $statement = mysqli_prepare($db, $sql);
    $result = mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);	

    echo '<table class="tabela">
    <tr>
    <th>Id</th>
    <th>Utilizador</th>
    <th>Comentário</th>
    <th>Dia</th></tr>';
        
      while( $row = mysqli_fetch_assoc($result) )
        {
          echo   
          '<tr><td>'.
          $row['id_comments'] . '</td><td>'.
          $row['user_name'].' ('.$row['id_user'].')'.'</td><td>'.
          $row['comment'] . '</td><td>'.
          $row['day'] . '</td>'.
          '</td></tr>';   }

        echo'</table>';
    ?>

</div>

    <footer style="margin-top:500px" class="site-footer">
        <div class="footertext">
          <h6>Sobre</h6>
            <p class="text-justify">A caderneta de cromos é um site fundado por três alunos de comunicação e multimédia que tem como conceito o poder de trocar cromos 
				                            do pokemon com outros utilizadores de todas as partes do mundo.</p>
        </div>
    </footer>