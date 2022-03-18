<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon</title>
		<link rel="stylesheet" href="style.css">
		<meta http-equiv="refresh" content="1000">
		<script src="https://kit.fontawesome.com/5ea815c1d0.js"></script>
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
				
		$iduser=$_SESSION['id'];


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
		
		
		
		//select all columns from all users in the table
		$query = "SELECT add_id, add_user, nomeuser, add_quantidade, add_nomepokemon ,add_pokemon FROM adicionar WHERE add_user='$iduser'";

  		//prepare the statement				
		$statement = mysqli_prepare($db, $query);
		$result = mysqli_stmt_execute($statement);
		$result = mysqli_stmt_get_result($statement);

				//select all columns from all users in the table
			$query5 = "SELECT add_id, add_user, nomeuser, add_quantidade, add_nomepokemon ,add_pokemon FROM adicionar WHERE add_pokemon=7";

				//prepare the statement				
			  $statement5 = mysqli_prepare($db, $query5);
			  $result5 = mysqli_stmt_execute($statement5);
			  $result5 = mysqli_stmt_get_result($statement5);

	//select all columns from all users in the table
	$query1 = "SELECT id_users, username, admini, email, pais, photo FROM users WHERE id_users='$iduser'";
  		
	//prepare the statement				
	$statement1 = mysqli_prepare($db, $query1);	
	$result1 = mysqli_stmt_execute($statement1);							  
	$result1 = mysqli_stmt_get_result($statement1);
	while( $row = mysqli_fetch_assoc($result1) ){
		{ 
			$nomeuser=$row['username'];
			$photo=$row['photo'];
			$email=$row['email'];
			$pais=$row['pais'];
		}
	}
?>

        <img src="<?php echo $photo ?> " class="capa1" alt="Foto Perfil"> 
        <div class="nomeu1"><?php echo $nomeuser ?></div>
        <div class="descr1"><br><?php echo 'Pais:'. $pais?> </div>
		<div class="descr2"><br><?php echo 'E-mail:<br>'. $email ?>  </div>
		<p class="avalie"> Avalie Este Utilizador </p>
		<input style="visibility: hidden;" value="<?php echo $email ?>"  id="myInput">


<form action="" method="POST" name="rating" class="rating">
  <label>
    <input type="submit" name="stars" value="1" />
    <span class="icon">★</span>
  </label>
  <label>
    <input type="submit" name="stars" value="2" />
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="submit" name="stars" value="3" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>   
  </label>
  <label>
    <input type="submit" name="stars" value="4" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="submit" name="stars" value="5" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
</form>


<?php 
$i=0;
$sum=0;
//contas 
	$query4= "SELECT id_rating, id_user1, id_user2, rating FROM rating WHERE id_user1=$iduser";
	$statement4 = mysqli_prepare($db, $query4);
	$result4 = mysqli_stmt_execute($statement4);
	$result4 = mysqli_stmt_get_result($statement4);	
	while( $row = mysqli_fetch_assoc($result4) )
	{ $i=$i+1; 
	$sum=$sum+$row['rating'];
	}
	echo $i;
	$media=$sum;
	if($media!=0){
	$media=$sum/$i;
	}
	$media=(round($media, 1));
	echo " ----(". $media."/5)";
//----------------------------------------
//---------------------busca dados onde o utilizador atual é o ID2 e --------------
	$query5= "SELECT id_rating, id_user1, id_user2, rating FROM rating WHERE id_user1=$iduser AND id_user2=$_SESSION[id]";
	$statement5 = mysqli_prepare($db, $query5);
	$result5 = mysqli_stmt_execute($statement5);
	$result5 = mysqli_stmt_get_result($statement5);	
	while( $row = mysqli_fetch_assoc($result5) )
	{	
			echo '<div class="avaliacao">Votaste '.$row['rating'].' Estrelas</div>';
	}

 if (isset($_POST['stars'])) { 
 $rating = $_POST["stars"];  
$id1=0;
		$query1= "SELECT id_rating, id_user1, id_user2 FROM rating WHERE id_user1=$iduser AND id_user2=$_SESSION[id]";
		$statement1 = mysqli_prepare($db, $query1);
		$result1 = mysqli_stmt_execute($statement1);
		$result1 = mysqli_stmt_get_result($statement1);	
		while( $row = mysqli_fetch_assoc($result1) )
		{
			$idrating=$row['id_rating'];
			$id1=$row['id_user1'];
			$id2=$row['id_user2'];
		}
			if($id1==$iduser){
				$query2= "UPDATE rating SET rating='$rating' WHERE id_rating=$idrating";
				$statement2 = mysqli_prepare($db, $query2);
				$result2 = mysqli_stmt_execute($statement2);
				echo("<meta http-equiv='refresh' content='0'>");
			}
			else{
				$query3 = "INSERT rating SET id_user1='$iduser', id_user2=$_SESSION[id], rating='$rating'" ;
				$statement3 = mysqli_prepare($db, $query3);
				$result3 = mysqli_stmt_execute($statement3);
				echo("<meta http-equiv='refresh' content='0'>");
				}   ; }
?>

<div class="titulop1">Que Pokemons Tem</div>


		<?php
		'<div style="text-decoration: none;">';
        echo '<table class="tabela">
		<tr>
		<th>Que Pokemon</th>
		<th>Quantidade</th></tr>' ;
		while( $row = mysqli_fetch_assoc($result) )
		{

      		echo 
			'<tr><td>' .
			$row['add_nomepokemon']. '</td><td>' . 
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

	<script>
	function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  alert("Copied the text: " + copyText.value);
}

$(':radio').change(function() {
  console.log('New star rating: ' + this.value);
});


</script>