<?php
session_start();
require_once './action/get_movies.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du film</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="@details.css">
</head>
<body>
    <h1><?= html_entity_decode(htmlspecialchars($film['titre'])); ?></h1>
    <p><strong>Année de sortie :</strong> <?= htmlspecialchars($film['annee_sortie']); ?> </p>
    <p><strong>Durée du film :</strong> <?= htmlspecialchars($film['duree']); ?> min</p>
    <p><strong>Synopsis :</strong> <?=html_entity_decode(htmlspecialchars($film['synopsis'])); ?></p>
    <a href="../accueil.php">Retour à la liste des films</a>
</body>
</html>
