<?php
// verification si utilisateur ou admin et affichage du role
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user = $_SESSION['username'];

if ($isAdmin) {
    echo "(Admin) " . $user;
} else {
    echo "(Utilisateur) " . $user;
}

