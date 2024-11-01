<?php 
session_start();
require_once './action/csrf.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription & Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="@inscription_connexion.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <?php // affiche erreur ou succes
                if (isset($_SESSION['errorInscription'])) {
                    echo "<div class='error'>" . htmlspecialchars($_SESSION['errorInscription']) . "</div>";
                    unset($_SESSION['errorInscription']);
                }
                if (isset($_SESSION['successInscription'])) {
                    echo "<div class='success'>" . htmlspecialchars($_SESSION['successInscription']) . "</div>";
                    unset($_SESSION['successInscription']);
                }
            ?>
            <h1>Inscription</h1>
            <form action="./action/inscription_action.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" name="password" required>

                <label for="email">Email :</label>
                <input type="email" name="email" required>

                <label for="role">Rôle :</label>
                <input type="radio" name="role" value="user" checked> Utilisateur
                <input type="radio" name="role" value="admin"> Admin

                <input type="submit" name="action" value="Inscription">
            </form>
        </div>

        <div class="form-container">
            <?php // affiche erreur si pas d'utilisateur
                if (isset($_SESSION['errorConnexion'])) {
                    echo "<div class='error'>" . htmlspecialchars($_SESSION['errorConnexion']) . "</div>";
                    unset($_SESSION['errorConnexion']);
                }
            ?>
            <h1>Connexion</h1>
            <form action="./action/connexion_action.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" name="password" required>

                <input type="submit" name="action" value="Connexion">
            </form>
        </div>
    </div>
</body>
</html>
