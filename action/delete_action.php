<?php
session_start();
require_once 'bdd.php';

// vérification du rôle de l'utilisateur 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('Accès refusé');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $filmId = $_POST['id'];

    // requête pour supprimer le film via son ID
    $deleteQuery = "DELETE FROM films WHERE id = :id";
    $sauvegarde = $connexion->prepare($deleteQuery);

    try { // on essaie de supprimer le film, si ça marche on redirige vers la page d'accueil sinon on affiche un message d'erreur
        $sauvegarde->execute(['id' => $filmId]);
        echo "Film supprimé avec succès.";
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression du film : " . $e->getMessage();
    }
}

header('Location: ../accueil.php');
exit();

