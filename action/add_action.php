<?php
session_start();
require_once 'bdd.php';

// vérification du rôle de l'utilisateur
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// vérification du jeton csrf
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    // vérifie que l'utilisateur est admin et que l'action est bien 'add'
    if ($isAdmin && isset($_POST['action']) && $_POST['action'] === 'add') {
        $titre = htmlspecialchars($_POST['titre']);
        $annee = htmlspecialchars($_POST['annee']);
        $duree = htmlspecialchars($_POST['duree']);
        $synopsis = htmlspecialchars($_POST['synopsis']);

        // requête pour ajouter le film à la base de données
        $query = "INSERT INTO films (titre, annee_sortie, duree, synopsis) VALUES (:titre, :annee, :duree, :synopsis)"; 
        $sauvegarde = $connexion->prepare($query);

        try {
            $sauvegarde->execute([
                'titre' => $titre,
                'annee' => $annee, 
                'duree' => $duree,
                'synopsis' => $synopsis
            ]);
            header('Location: ../accueil.php'); 
            exit();
        } catch (PDOException $e) {
            // stocke le message d'erreur dans la session
            $_SESSION['errorAdd'] = "Erreur lors de l'ajout du film : " . $e->getMessage();
            header('Location: ../accueil.php');
            exit();
        }
    } else {
        echo "action non autorisée.";
        exit();
    }
} else {
    echo "erreur csrf. opération non autorisée.";
    exit();
}
