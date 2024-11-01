<?php
session_start();
require_once 'bdd.php';


// vérification du rôle de l'utilisateur s'il n'est pas admin on le redirige vers la page d'accueil
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../accueil.php');
    exit();
}

// vérification de la méthode de la requête et de l'existence de l'ID du film
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $titre = htmlspecialchars($_POST['titre']);
    $annee = htmlspecialchars($_POST['annee']);
    $duree = htmlspecialchars($_POST['duree']);
    $synopsis = htmlspecialchars($_POST['synopsis']);

    $query = "UPDATE films SET titre = :titre, annee_sortie = :annee, duree = :duree, synopsis = :synopsis WHERE id = :id";
    $sauvegarde = $connexion->prepare($query);

    // on essaie de modifier le film, si ça marche on redirige vers la page d'accueil sinon on affiche un message d'erreur
    try {
        $sauvegarde->execute([
            'id' => $id,
            'titre' => $titre,
            'annee' => $annee,
            'duree' => $duree,
            'synopsis' => $synopsis
        ]);
        header('Location: ../accueil.php');
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la modification du film : " . $e->getMessage();
    }
} else {
    header('Location: ../accueil.php');
    exit();
}
