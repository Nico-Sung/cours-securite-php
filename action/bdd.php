<?php // recupération de la base de données
try{
        $connexion = new PDO ('mysql:host=localhost;dbname=Exercice;port=3306', 'root', 'root');
    }
    catch(Exception $e){
        die($e->getMessage());
    }