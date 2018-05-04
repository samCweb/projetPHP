<?php 

include_once 'menu.php'; 
session_start(); 


if(isset($_SESSION['users']['email'])){
    $sex = htmlspecialchars($_SESSION['users']['sex']);
    $name = htmlspecialchars($_SESSION['users']['name']);
    $firstname = htmlspecialchars($_SESSION['users']['firstname']);
    $email = htmlspecialchars($_SESSION['users']['email']);
    $register_date = ($_SESSION['users']['date']);
    $photo = $_SESSION['users']['photo'];

} else {
    $error = '<h1>Merci de vous connecter !</h1>';
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
    
    if(!isset($error)){
        
    
    ?>

        <p> Votre civilité :
            <?php 
           echo $sex;
        ?>
        </p>
        <p> Votre nom :
            <?php 
           echo $name;
        ?>
        </p>
        <p> Votre prénom :
            <?php 
           echo $firstname;
        ?>
        </p>
        <p> Votre email :
            <?php 
           echo $email;
        ?>
        </p>
        <p> Votre photo :
            <?php 
           echo $photo;
        ?>
        </p>
        <p> Vous vous êtes inscrit le :
            <?php 
           echo $register_date;
        ?>
        </p>



        <?php
    }
    if(isset($error)){
        
        echo $error;
    }
    
    ?>

</body>


</html>
