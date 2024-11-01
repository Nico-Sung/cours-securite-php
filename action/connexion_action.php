<?php
session_start();
require_once 'bdd.php';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // genere un nouveau token CSRF
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'Connexion') { 
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) { // si le token CSRF n'est pas valide on arrete le script et on affiche un message d'erreur
        die('CSRF token invalide');
    }

    unset($_SESSION['csrf_token']); // on supprime le token CSRF pour eviter sa reutilisation

    $username = htmlspecialchars($_POST['username']); 
    $password = htmlspecialchars($_POST['password']);

    $query = "SELECT * FROM utilisateurs WHERE username = :username"; // on recupere l'utilisateur avec le nom d'utilisateur fourni
    $sauvegarde = $connexion->prepare($query); 
    $sauvegarde->execute(['username' => $username]); 
    $user = $sauvegarde->fetch(); // on recupere la premiere ligne de resultat

    // si l'utilisateur existe et que le mot de passe correspond au mot de passe hashé dans la base de données on stocke le role et le nom d'utilisateur dans la session
    if ($user && password_verify($password, $user['password'])) { 
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $username;
        header('Location: ../accueil.php'); // et on redirige l'utilisateur vers la page d'accueil
    } else { // sinon on stocke un message d'erreur dans la session et on redirige l'utilisateur vers la page de connexion
        $_SESSION['errorConnexion'] = "Nom d'utilisateur ou mot de passe incorrect.";
        header('Location: ../index.php');
    }
    exit();
}
