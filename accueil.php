<?php
session_start();
require_once './action/bdd.php';
require_once './action/csrf.php';
require_once './action/verifier_role.php'; 

$query = "SELECT * FROM films ORDER BY annee_sortie DESC"; // récupère les films selectionnés par ordre décroissant de l'année de sortie
$result = $connexion->query($query);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Films</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="@accueil.css">
</head>
<body>
    <div class="container">
        <h1>Liste des Films</h1>
        <?php if (isset($_SESSION['errorAdd'])): ?>
            <div class="errorAdd"><?= htmlspecialchars($_SESSION['errorAdd']); ?></div>
            <?php unset($_SESSION['errorAdd']); ?>
        <?php endif; ?>
        <?php if ($isAdmin): ?> <!-- si admin, montre la section "ajout" -->
            <form id="add" action="./action/add_action.php" method="POST" >
                <h2>Ajouter un film</h2>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="action" value="add">
                <label for="titre">Titre du film :</label>
                <input type="text" name="titre" required>
                <br>
                <label for="annee">Année de sortie :</label>
                <input type="number" name="annee" required>
                <br>
                <label for="duree">Durée du film (min) :</label>
                <input type="number" name="duree" required>
                <br>
                <label for="synopsis">Synopsis :</label>
                <textarea name="synopsis" required></textarea>
                <br>
                <input type="submit" value="Ajouter Film">
            </form>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>Titre du film</th>
                    <th>Année de sortie</th>
                    <th>Durée du film (en min)</th>
                    <th>Synopsis</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->rowCount() > 0): ?>
                    <?php while ($film = $result->fetch()): ?>
                        <tr>
                            <td><?= html_entity_decode(htmlspecialchars($film['titre'])); ?></td> <!-- enleve le bug de l'apostrophe -->
                            <td><?= html_entity_decode(htmlspecialchars($film['annee_sortie'])); ?></td>
                            <td><?= html_entity_decode(htmlspecialchars($film['duree'])); ?> min</td>
                            <td><?= html_entity_decode(htmlspecialchars($film['synopsis'])); ?></td>
                            <td>
                                <?php if ($isAdmin): ?>
                                    <button onclick="openModal(<?= $film['id']; ?>,
                                    '<?= html_entity_decode(htmlspecialchars($film['titre'])); ?>',
                                    <?= isset($film['annee_sortie']) ? $film['annee_sortie'] : 'null';  ?>, // si l'année de sortie n'est pas définie, renvoie null
                                    '<?= html_entity_decode(htmlspecialchars($film['duree'])); ?>',
                                    '<?= html_entity_decode(htmlspecialchars($film['synopsis'])); ?>')">Modifier</button>
                                    <form action="./action/delete_action.php" method="POST" style="display:inline">
                                        <input type="hidden" name="id" value="<?= $film['id']; ?>">
                                        <input type="submit" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce film ?');">
                                    </form>
                                <?php endif; ?>
                                <a href="details.php?id=<?= $film['id']; ?>">Consulter</a> <!-- lien vers la page de détails pour admin et user -->
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun film trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Modifier le Film</h2>
            <form id="editForm" action="./action/edit_action.php" method="POST">
                <input type="hidden" name="id" id="filmId">
                <label for="titre">Titre :</label>
                <input type="text" name="titre" id="titre" required>
                <br>
                <label for="annee">Année :</label>
                <input type="number" name="annee" id="annee" required>
                <br>
                <label for="duree">Durée :</label>
                <input type="text" name="duree" id="duree" required>
                <br>
                <label for="synopsis">Synopsis :</label>
                <textarea name="synopsis" id="synopsis" required></textarea>
                <br>
                <input type="submit" value="Modifier">
            </form>
        </div>
    </div>
    <script>
        function openModal(id, titre, annee, duree, synopsis) {
            document.getElementById("filmId").value = id;
            document.getElementById("titre").value = titre ?? ''; 
            document.getElementById("annee").value = annee ?? '';
            document.getElementById("duree").value = duree ?? ''; 
            document.getElementById("synopsis").value = synopsis ?? ''; 
            document.getElementById("modal").style.display = "block";
        }
        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById("modal")) {
                closeModal();
            }
        }
    </script>
</body>
</html>