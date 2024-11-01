<?php
session_start();
require_once 'bdd.php';

// verifie si l'ID du film est passé dans l'URL
if (isset($_GET['title'])) {
    $filmId = $_GET['title']; 

    // requete pour récupérer les détails du film
    $query = "SELECT * FROM films WHERE title = :title";
    $sauvegarde = $connexion->prepare($query);
    $sauvegarde->execute(['id' => $filmId]);
    $film = $sauvegarde->fetch();

    // verifie si le film existe sinon affiche un message d'erreur soit le film n'existe pas soit l'ID est incorrect
    if (!$film) {
        echo "Film non trouvé.";
        exit();
    }
} else {
    echo "ID de film manquant.";
    exit();
}

