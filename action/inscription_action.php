<?php
session_start();
require_once 'bdd.php';

// on genere un token CSRF s'il n'existe pas déjà
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'Inscription') {
    //vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token invalide');
    }

    unset($_SESSION['csrf_token']);

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    // verifie si l'utilisateur existe déjà
    $checkQuery = "SELECT * FROM utilisateurs WHERE username = :username OR email = :email";
    $checkSauvegarde = $connexion->prepare($checkQuery);
    $checkSauvegarde->execute(['username' => $username, 'email' => $email]);
    $existingUser = $checkSauvegarde->fetch(PDO::FETCH_ASSOC);

    // si l'utilisateur existe déjà on affiche un message d'erreur
    if ($existingUser) {
        $_SESSION['error'] = "Nom d'utilisateur ou email déjà utilisé.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // on hash le mot de passe
        $query = "INSERT INTO utilisateurs (username, password, email, role) VALUES (:username, :password, :email, :role)";
        $sauvegarde = $connexion->prepare($query);

        // on essaie d'ajouter l'utilisateur à la base de données, si ça marche on affiche un message de succès sinon un message d'erreur qu'on stocke dans la session
        try {
            $sauvegarde->execute(['username' => $username, 'password' => $hashed_password, 'email' => $email, 'role' => $role]);
            $_SESSION['successInscription'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
        } catch (PDOException $e) {
            $_SESSION['errorInscription'] = "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }

    header('Location: ../index.php'); // si tout s'est bien passé on redirige l'utilisateur vers la page de connexion
    exit();
}
