<?php
include 'cookies/header.php';
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

//guarda os Valores Em Váriaveis Locais
$iduser=$_SESSION['id'];
$user_name=$_SESSION['username'];
$comment= $_POST['comment'];
$today=date("Y-m-d");


  //Verifica se o comentário tem Caracteres Especiais
  if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $comment))
    {
      echo "ERROR";
      header('Location:pokemon.php');
      die(); }

  //insere o comentario na base dados
  $query1 = "INSERT comments SET id_user='$iduser',user_name='$user_name', comment='$comment', day='$today'";
  $statement1 = mysqli_prepare($db, $query1);
  $result1 = mysqli_stmt_execute($statement1);
  $result1 = mysqli_stmt_get_result($statement1);

    $_SESSION['comentario']=1;

    header('Location:pokemon.php');
/*----------------------------------------------------------------------------------*/

?>