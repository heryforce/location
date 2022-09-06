<?php
session_start();

// pour déconnecter un utilisateur, je vide toutes les variables de session que j'ai créees lors de la connexion, puis je détruis la session
$_SESSION = array();
session_destroy();
header("location: connexion.php");
?>