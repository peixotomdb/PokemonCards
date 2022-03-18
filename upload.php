<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon</title>
        <link rel="stylesheet" href="style.css">
		<meta http-equiv="refresh" content="0">
    </head>

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

$id=$_SESSION['id'];


if (!isset($_FILES["myFile"])) {
    die("There is no file to upload.");
}
else{ 
   ' <meta http-equiv="refresh" content="0">';


$filepath = $_FILES['myFile']['tmp_name'];
$fileSize = filesize($filepath);
$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
$filetype = finfo_file($fileinfo, $filepath);

if ($fileSize === 0) {
    die("The file is empty.");
}

if ($fileSize > 2097152) { // 2 MB (1 byte * 1024 * 1024 * 2 (for 2 MB))
    die("The file is too large");
}

$allowedTypes = [
   'image/png' => 'png',
   'image/jpeg' => 'jpg',
   'image/gif' => 'gif'
];

if (!in_array($filetype, array_keys($allowedTypes))) {
    die("File not allowed.");   }


$filename = basename($_SESSION['username']); // AQUI .I'm using the original name here, but you can also change the name of the file here
$extension = $allowedTypes[$filetype];
$targetDirectory = __DIR__ . "/uploads"; // __DIR__ is the directory of the current PHP file
$newFilepath = $targetDirectory . "/" . $filename . "." . "gif";
$foto= "uploads/" . $filename . "." . "gif";

$name=__DIR__ ."/uploads/" . $_SESSION['username']. "." . $allowedTypes[$file0'type] ;

if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
    die("Can't move file.");
}
unlink($filepath); // Delete the temp file


    $sql = "UPDATE users SET photo='$foto' WHERE id_users=$id";
    $statement = mysqli_prepare($db, $sql);
    $result = mysqli_stmt_execute($statement);

    header("Location:pokemon.php");

}
?>




