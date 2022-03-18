<?php

  include 'cookies/header.php';

    if(isset($_POST['submit'])){
      $quantidade = $_POST['quantidade'];  // Storing Selected Value In Variable
      $idpokemon = $_POST['idpokemon'];  // Storing Selected Value In Variable
    }

    $iduser=$_SESSION['id'];


//------------------ir buscar o nome pelo id da SESSION------------------------------------------
require_once('cookies/configDb.php');
$db = connectDB();

$sql= " SELECT username FROM `users` WHERE id_users=$iduser";	
$statement = mysqli_prepare($db, $sql);
$result = mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);	
while( $row = mysqli_fetch_assoc($result) )
{
    $nomeuser=$row['username'];
}	
//--------------------------------------------------------------------------------------------

//-------------------- para buscar o NoME DO POKEMON------------------------------------------
$sql1= " SELECT nome FROM `pokemon` WHERE id_pokemon=$idpokemon";
$statement1 = mysqli_prepare($db, $sql1);
  $result1 = mysqli_stmt_execute($statement1);
  $result1 = mysqli_stmt_get_result($statement1);	
while( $row = mysqli_fetch_assoc($result1) )
{
     $noome=$row['nome'];
}	
//--------------------------------------------------------------------------------------------


//$iduser         id do utilizador
//$nomeuser       nome do utilizador
//$idpokemon      pokemon selecionado
//$quantidade     quantidade selecionado

//------- buscar a quantidade de cartas adicionadas pelo USER da SESSION e com o ID do POKEMON escolhido

    $sql2= " SELECT add_quantidade FROM `adicionar` WHERE add_user=$iduser AND add_pokemon=$idpokemon";	
    $statement2 = mysqli_prepare($db, $sql2);
    $result2 = mysqli_stmt_execute($statement2);
    $result2 = mysqli_stmt_get_result($statement2);	

  while( $row = mysqli_fetch_assoc($result2) )
  {
        $tabela=$row['add_quantidade'];
  }	

// se a quantidade da tabela ADICIONAR for MAIOR que a QUANTIDADE que QUERO REMOVER
  if ($tabela>=$quantidade){
    $resu=$tabela-$quantidade;

    $_SESSION['suc']= "o utilizador " .$nomeuser . "(". $iduser . ") removeu ". $quantidade. " " . $noome 
    ." <form method='get' action='pokemon.php'> <button type='submit'>Voltar</button> </form> ";

  }
  else{
    
    $_SESSION['suc']= "o utilizador " .$nomeuser . "(". $iduser . ") TENTOU REMOVER MAIS DO QUE TINHA "
    ." <form method='get' action='pokemon.php'> <button type='submit'>Voltar</button> </form> ";
  }

//--------------------------------------------------------------------------------------------
//$iduser         id do utilizador
//$nomeuser       nome do utilizador
//$idpokemon      pokemon selecionado
//$quantidade     quantidade selecionado

//----------------------------Update à QUANTIDADE COM O VALOR REMOVIDO (no) ID DO USER (e) IDPOKEMON
  $sql3= "UPDATE adicionar SET add_quantidade=$resu WHERE add_user=$iduser AND add_pokemon=$idpokemon";

//---------------- OUTRO UPDATE SO QUE Á TABELA POKEMON para RETIRAR a quantidade no IDPOKEMON APENAS
  $sql4= "UPDATE pokemon SET quantidade=$resu WHERE id_pokemon=$idpokemon";

  header('Location:adicionar.php');

?>