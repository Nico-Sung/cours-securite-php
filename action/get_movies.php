<?php
require_once './action/bdd.php';

// verifie si l'ID du film est passé dans l'URL
if (isset($_GET['id'])) {
    $filmId = $_GET['id']; 

    // requete pour récupérer les détails du film
    $query = "SELECT * FROM films WHERE id = :id";
    $sauvegarde = $connexion->prepare($query);
    $sauvegarde->execute(['id' => $filmId]);
    $film = $sauvegarde->fetch();

    // on verifie si le film existe
    if (!$film) {
        echo "Film non trouvé.";
        exit();
    }
} else { // si pas d'id existant alors on affiche un autre message d'erreur
    echo "ID de film manquant.";
    exit();
}
