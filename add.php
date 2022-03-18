<?php

//----------------------------CONECTAR À BASE DE DADOS----------------------------------------
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "daw";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

//---------------------------------------------------------------------------------------------
//---------------Busca O Header E Verifica Se Está Autenticado------------------------
include 'cookies/header.php';
require_once('cookies/configDb.php');
$db = connectDB();

//---------------Verifica se existe algo dentro do submit------------------------
if(isset($_POST['submit'])){
$quantidade = $_POST['quantidade'];  //A quantidade Selecionada Guarda Valores do Post numa Variavel
$idpokemon = $_POST['idpokemon'];  //O Pokemon Selecionado Guarda Valores do Post numa Variavel
}
//---------------Guarda o ID da SESSAO dentro da variavel $iduser------------------------

$iduser=$_SESSION['id'];

//---------------Seleciona Nome Da tabela Pokemon ONDE o id Pokemon é o Selecionado anteriormente------------------------
$sql3= " SELECT nome FROM `pokemon` WHERE id_pokemon=$idpokemon";
$statement12 = mysqli_prepare($db, $sql3);
$result12 = mysqli_stmt_execute($statement12);
$result12 = mysqli_stmt_get_result($statement12);	
while( $row = mysqli_fetch_assoc($result12) )
{
  //---------------Guarda a row dentro da variavel $noome------------------------
     $noome=$row['nome'];
}	
 
  //---------------Seleciona Username ONDE idusers é o id da sessao------------------------
$sql4= " SELECT username FROM `users` WHERE id_users=$iduser";	
$statement2 = mysqli_prepare($db, $sql4);
$result2 = mysqli_stmt_execute($statement2);
$result2 = mysqli_stmt_get_result($statement2);	
while( $row = mysqli_fetch_assoc($result2) )
{
   //---------------Guarda a row dentro da variavel $nomeuser------------------------
     $nomeuser=$row['username'];
}	

 //---------------Seleciona quantidade ONDE id pokemon é o id pokemon selecionado anteriormente------------------------
$sql1= " SELECT quantidade FROM `pokemon` WHERE id_pokemon=$idpokemon";	
$statementa = mysqli_prepare($db, $sql1);
$result = mysqli_stmt_execute($statementa);
$resulta = mysqli_stmt_get_result($statementa);	
  while( $row = mysqli_fetch_assoc($resulta) )
  {
     //---------------Soma a quantidade ao Valor que o Utilizador Selecionou------------------------
      $sum1=$row['quantidade'];
      $sum1=$sum1+$quantidade;
  }	

// -------------Vai À Base de Dados e Verifica se existe Alguma Entrada Igual

  $sql5= "SELECT add_id, add_user, add_pokemon FROM `adicionar` WHERE add_user=$iduser AND add_pokemon=$idpokemon";
  $statement3 = mysqli_prepare($db, $sql5);
  $result3 = mysqli_stmt_execute($statement3);
  $result3 = mysqli_stmt_get_result($statement3);	

      while( $row = mysqli_fetch_assoc($result3) )
      {
        //-------------Se Exisitir ele Guarda Os Valores Nestas Variaveis------------------------
          $tabela =$row['add_pokemon'];
          $addid  =$row['add_id'];
      }
 //IGUAL-------------Se Os Valores Guardados Forem Iguais ------------------------
        if ($tabela==$idpokemon){
            //-------------DÁ update À Quantidade NO ADICIONAR ------------------------
              $sql2= "UPDATE adicionar SET add_quantidade='$sum1' WHERE add_id=$addid";

                //-------------DÁ update À Quantidade NO POKEMON ------------------------
              $sql2= "UPDATE pokemon SET quantidade='$sum1' WHERE id_pokemon=$idpokemon";

                //-------------Guarda a Mensagem dentro do SESSION ´suc´ ------------------------
              $_SESSION['suc']= "o utilizador " .$nomeuser . "(". $iduser . ") " ." adicionou à tabela existente ". $quantidade. " " . $noome 
                      ." <form method='get' action='pokemon.php'> <button type='submit'>Voltar</button> </form> ";
            }

//DIFERENTE-------------Se Os Valores Guardados Forem Diferentes ------------------------
        else{
          //-------------CRIAR UMA LINHA NOVA NA TABELA ADICIONAR------------------------
              $sql = "INSERT adicionar SET add_user='$iduser', nomeuser='$nomeuser', add_pokemon='$idpokemon', add_nomepokemon='$noome', add_quantidade='$quantidade'";
                if ($conn->query($sql) === TRUE) {
                $_SESSION['sucess'] = '1';  } 

              else {
              $_SESSION['sucess'] = '0';
              }
//------------------------------------------------------------------------------

              $sql3= " SELECT nome FROM pokemon WHERE id_pokemon=$idpokemon";
              $statement = mysqli_prepare($db, $sql3);
                $result = mysqli_stmt_execute($statement);
                $result = mysqli_stmt_get_result($statement);	
                while( $row = mysqli_fetch_assoc($result)){ 
//---------------Guarda a row dentro da variavel $nomeuser------------------------
                   $noome=$row['nome'];
                }	
            
          //------------VAI DAR UPDATE À QUANTIDADE no pokemon E TROCAR PELA SOMA--------------------------------------------------     
              $sql2= "UPDATE pokemon SET quantidade='$sum1' WHERE id_pokemon=$idpokemon";

            //-------------Guarda a Mensagem dentro do SESSION ´suc´ ------------------------     
                $_SESSION['suc']= "o utilizador  " .$nomeuser . "(". $iduser . ") " ." criou uma tabela nova ". $quantidade. " " . $noome 
                  ." <form method='get' action='pokemon.php'> <button type='submit'>Voltar</button> </form> ";

            }  //fechar else
            
  header('Location:adicionar.php'); //vai para o adicionar.php
  $conn->close();



?>