<?php 
session_start();

include_once 'menu.php';

if(isset($_SESSION['users']['email'])){
    session_destroy();
    $logout = '<h1>Vous êtes déconnectés ! <br/> Veuillez vous <a href="connexion.php"> connecter </a>';
   
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>projetPHP </title>
    <style>


    </style>
    <link href="style.css" rel="stylesheet">
</head>

<html>

<body>

    <?php
        
        if(isset($logout)){
            
        echo '<h1>Vous êtes déconnectés ! <br/> Veuillez vous <a href="connexion.php"> connecter </a>';
        
        }
        ?>
</body>


</html>
