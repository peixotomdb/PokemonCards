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


<?php
        //se o GET favorito estiver vazio nao faz nada
        if(!isset($_GET['favorito'])){
            $favorito="";
        }
        else{
            //se o GET favorito tiver valor guarda na Variavel $favotiro
            $favorito=$_GET['favorito'];
        }
        //Definir para nulo as Variaveis
        $_SESSION['error1']="";
        $_SESSION['error2']="";

//--------------------Mensagens de erro-----
    $query2="SELECT * FROM adicionar WHERE add_pokemon='$favorito' AND add_user='$_SESSION[id]'";
    $statement2 = mysqli_prepare($db, $query2);
    $result2 = mysqli_stmt_execute($statement2);
    $result2 = mysqli_stmt_get_result($statement2);
    while( $row = mysqli_fetch_assoc($result2) ){

            $_SESSION['error1']=$row['nomeuser']." Já tens ".$row['add_quantidade'].' '.$row['add_nomepokemon']." não podes adicionar este a lista";
            header("Location:pokemon.php");
            die();
            $_SESSION['error1']="";
    }


//----------------------------------Mensagem de erro-----
    $query1="SELECT id_list, who FROM list WHERE what='$favorito'";
    $statement1 = mysqli_prepare($db, $query1);
    $result1 = mysqli_stmt_execute($statement1);
    $result1 = mysqli_stmt_get_result($statement1);
    while( $row = mysqli_fetch_assoc($result1) ){

        if($_SESSION['id']==$row['who']){
            $_SESSION['error2']= "Já Adicionaste Este Pokemon a Tua Lista de Desejos";
            header("Location:pokemon.php");
            die();
            $_SESSION['error2']="";
        }
    }
//-------------------vai inserir na tabela list quem e o que meteu-----------
        $query="INSERT list SET who='$_SESSION[id]',what='$favorito'";
            $statement = mysqli_prepare($db, $query);
            $result = mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);	



//-------------------vai buscar a tabela pokemon e comeca a escrever tudo-----
		$sql = "SELECT * FROM list WHERE who='$_SESSION[id]'";
		$statement = mysqli_prepare($db, $sql);
		$result = mysqli_stmt_execute($statement);
		$result = mysqli_stmt_get_result($statement);	
		while( $row = mysqli_fetch_assoc($result) ){

            $what=$row['what'];
            $sql1 = "SELECT * FROM pokemon WHERE id_pokemon=$what";
            $statement1 = mysqli_prepare($db, $sql1);
            $result1 = mysqli_stmt_execute($statement1);
            $result1 = mysqli_stmt_get_result($statement1);	
            while( $row = mysqli_fetch_assoc($result1) )
            { 
            echo '<br><table stle="margin-left:400px" class="tabela">
                <tr></th>
                <th>Nome </th></th>
                <th>Categoria </th></th>
                <th>Quantidade</th>
                <th>Ver Pokemon</th></tr>
                <tr><td>'.
    
                $row['nome'] . '</a></td><td>' .
                $row['categoria'] . '</a></td><td>' . 	
                $row['quantidade'].'</a></td><td>
                    <form action=pokemonpage.php>
			        <input name=idpagepokemon type=hidden value="'.$row['id_pokemon'].'">
			        <span><input type=submit class="button-75" style="font-size: 15px; height:30px;line-height: 0px;" name=submit value='.$row['nome'].'></span>
			        <img style="margin-left:30px; position:absolute; opacity:0.5" class="img1" src="'.$row['photo'].$row['nome'].'.png""/><img class="img1" src="'.$row['photo'].$row['nome'].'.png""/>
			        </form> </td>
                </tr></table>';
            
                //select all columns from all users in the table
                $query5 = "SELECT * FROM adicionar WHERE add_pokemon='$row[id_pokemon]'";
            }

                $statement5 = mysqli_prepare($db, $query5);
                $result5 = mysqli_stmt_execute($statement5);
                $result5 = mysqli_stmt_get_result($statement5);
                while( $row = mysqli_fetch_assoc($result5) )
                {

                    echo '<div style="text-align:center; margin-top:20px;">'.
                    $row['nomeuser'].' têm '.$row['add_quantidade'].'  '; }	
        ;}

    

        //header("Location:pokemon.php");