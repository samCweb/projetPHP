<?php
session_start();
require 'recaptcha_valid.php';
include_once 'menu.php';

if(isset($_POST['sex'])&& isset($_POST['name'])&& isset($_POST['firstname'])&& isset($_POST['email'])&& isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['g-recaptcha-response'])) {
    
    /*indentation et aeration du code */
    
    /* vérification de la civilité*/
    if ($_POST['sex'] != 'M' && $_POST['sex'] != 'F'){
        
        $errors[] = "Civilité incorrecte";
    }   
    
    /* vérification du nom*/
    if (!preg_match('#^[a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{2,500}$#i',$_POST['name'])){

        $errors[]= "Nom incorrecte";
    }
    
    /* vérification du prénom*/
    if (!preg_match('#^[a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{2,500}$#i',$_POST['firstname'])){

        $errors[]= "Prénom incorrecte";
         
    }
    
    /* vérification de l'email*/
    if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
        
        $errors[]= "Email invalide";
        
    }
         
    /* vérification du mot de passe*/
    if (!preg_match('#^[0-9a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,300}$#i',$_POST['password'])){

        $errors[]= "Format de mot de passe invalide";
         
    }
    
    if($_POST['password'] != $_POST['password_confirm']){
            
        $errors[] = "Les mots de passe ne correspondent pas !";
    }
    
    if(!recaptcha_valid($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'])){
        $errors[]= 'Captcha invalide !';
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
        
        /*Vérification si l'email est déjà dans la base de données */
        $response = $bdd->prepare("SELECT email FROM users WHERE email = :email");
        $response->bindValue('email',$_POST['email']);

        $response->execute();

        $verifEmail = $response->fetch();
        
        /*Si new email -> inscription dans la bdd */
        if (empty($verifEmail)){
            
            /*insertion de la date */
            setlocale(LC_TIME, 'fr_FR.utf8', 'fra', 'fr_FR.iso8859-1', 'fr_FR', 'french', 'French_France.1252');
            $date= strftime('%Y-%m-%d %H-%M-%S'); 
            
            $response = $bdd->prepare("INSERT INTO users (sex, name, firstname, email, password, register_date, activate) VALUES (:sex, :name, :firstname, :email, :password, :register_date, :activate)");

            $response->bindValue('sex',$_POST['sex']);
            $response->bindValue('name',$_POST['name']);
            $response->bindValue('firstname',$_POST['firstname']);
            $response->bindValue('email',$_POST['email']);
            $response->bindValue('password',password_hash($_POST['password'], PASSWORD_BCRYPT));
            $response->bindValue('register_date', $date);
            
            $response->bindValue('activate', 0);

            $response->execute();


            $success = "Votre inscription est prise en compte ! <br/><br/> Merci de confirmer votre inscription en cliquant sur le lien reçu par email !";
        
        /* Si email KO -> invitation à se connecter*/
        }else {

            $errors[] = 'Votre email est déjà utilisé ! <br/><br/> Merci de vous <a href="connexion.php">connecter</a> !';
        }
        
    }
}
    
    
?>



    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <title>Exercice </title>
        <style>


        </style>
        <link href="style.css" rel="stylesheet">
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>

    <body>

        <div>
            <h1> Page Inscription</h1>
        </div>

        <div id="form">
            <form action="inscription.php" method="POST">

                <label for="sex">Civilité</label>
                <select name="sex" id="sex">
                
                    <option value="M">Monsieur</option>
                    <option value="F">Madame</option>
                
                </select><br/><br/>

                <label for="name">Nom</label>
                <input type="text" name="name" id="name" placeholder="Nom"><br/><br/>

                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname" placeholder="Prénom"><br/><br/>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email"><br/><br/>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe"><br/><br/>

                <label for="password_confirm">Confirmation du Mot de passe</label>
                <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmation Mot de passe"><br/><br/>

                <div class="g-recaptcha" data-sitekey="6LeJOVcUAAAAAE5R963ZdJlxZFq5izlk4J3n_td-"></div><br/><br/>

                <!-- PHOTO A VOIR <input type="text" name="name"  placeholder=""> -->

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
