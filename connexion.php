<?php 
session_start();

include_once 'menu.php';

if(isset($_POST['email'])&& isset($_POST['password'])) {
    
    /*indentation et aeration du code */
    
    
    /* vérification de l'email*/
    if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
        
        $errors[]= "Email invalide";
        
    }
         
    /* vérification du mot de passe*/
    if (!preg_match('#^.{3,300}$#i',$_POST['password'])){

        $errors[]= "Mot de passe invalide";
         
    }
    
    
    /*Si pas d'erreurs, connexion à la base de données */
    if(!isset($errors)){
        
        try {

            $bdd= new PDO('mysql:host=localhost;dbname=exercices;charset=utf8','root','');


        }catch(Exception $e){
            die($e->getMessage());
        }
        
        /*Vérification erreur */
        /*$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);*/
        
        /*Vérification si un compte existe avec l'email */
        $response = $bdd->prepare("SELECT * FROM users WHERE email = :email");
        $response->bindValue('email',$_POST['email']);

        $response->execute();

        $verif = $response->fetch();
       
        
        /*Si pas email -> erreur */
        if (empty($verif['email'])){
            
            $errors[] = 'Compte inexistant! Merci de vous <a href="inscription.php">inscrire</a> !';
 
        
        /* Si email OK -> vérification du mot de passe*/
        } else {
        
            if (!password_verify($_POST['password'],$verif['password'])){

                $errors[] = 'Erreur de mot de passe !';


            /* Si mot de passe OK */
            }else {

                $success = 'Vous êtes connectés ! <br/><br/> Accédez à votre <a href="profil.php">profil</a> !';
                
                
                $_SESSION['users'] = array(
                    'email' => $verif['email'],
                    'name' => $verif['name'],
                    'firstname' => $verif['firstname'],
                    'id' => $verif['id'],
                    'date' => $verif['register_date'],
                    'sex' => $verif['sex'],
                    'photo' => $verif['photo']
                );
                
                
                
            }
        }
        
    }
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

<body>

    <div>
        <h1> Page Connexion</h1>
    </div>

    <div id="form">
        <form action="connexion.php" method="POST">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Email"><br/><br/>

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe"><br/><br/>


            <input type="submit">

        </form>
    </div>

    <div id="message">
        <?php

    if(isset($errors)){
        
        foreach($errors as $error){
            
            echo '<p style="color:darkred; text-align:center; fontweight:bold;">'. $error . "</p>";
        }
    }
     if(isset($success)){
        
            
        echo '<p style="color:darkgreen; text-align:center; fontweight:bold;">'. $success . "</p>";
    }
?>
    </div>
</body>

</html>
