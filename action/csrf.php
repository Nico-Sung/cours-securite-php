<?php 
if( // si le token n'existe pas ou est vide on le genere et on le stocke en session 
    !isset($_SESSION['csrf_token']) ||
    empty($_SESSION['csrf_token'])
){
    $_SESSION['csrf_token'] = bin2hex(string : random_bytes(length: 32)) ;
}
