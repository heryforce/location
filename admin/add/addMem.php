<?php

require_once(__DIR__ . "/../inc/adminHeader.php");
require_once(__DIR__ . "/../../inc/db_functions.php");

if (isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['username']) && isset($_POST['admin'])) {

    $err = "";

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (iconv_strlen($_POST['mdp']) < 5)
        $err .= "Le mot de passe doit être composé d'au moins 5 caractères.<br>";

    if (iconv_strlen($_POST['username']) < 5)
        $err .= "Le nom d'utilisateur doit être composé d'au moins 5 caractères.<br>";

    if (isKnown($email, $_POST['username']))
        $err .= "Cette adresse mail ou ce nom d'utilisateur existe déjà.<br>";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $err .= "Cette adresse mail n'est pas valide.<br>";

    if (empty($err)) {
        try {
            $mdp = password_hash($_POST['mdp'], PASSWORD_BCRYPT); // password_hash() hashe le mdp en paramètre avec le hashage demandé (ici, Bcrypt)
            $q = getPdo()->prepare("INSERT INTO membre (email, mdp, username, admin) VALUES (?, ?, ?, ?)");
            $q->execute(array($email, $mdp, $_POST['username'], $_POST['admin']));
        } catch (PDOException $e) {
            printError("Erreur lors de l'insertion : " . $e->getMessage());
        }
        header("location: ../membres.php");
    }
}

if (!empty($err)) {
    printError($err); // s'il y a eu au moins une erreur, je l'affiche
}

?>

<a href="../membres.php" class="btn btn-success mt-2">Retour à la liste</a>

<h2 class="text-center">Ajout d'un membre</h2>

<form method="post">

    <label for="username" class="form-label fw-bold">Nom d'utilisateur</label>
    <input type="text" name="username" id="username" class="form-control" required>

    <label for="email" class="form-label fw-bold">Adresse mail</label>
    <input type="text" name="email" id="email" class="form-control" required>

    <label for="mdp" class="form-label fw-bold">Mot de passe</label>
    <input type="password" name="mdp" id="mdp" class="form-control" required>
    <div class="form-text  ">Il doit être composé d'au moins 5 caractères</div>

    <label for="admin" class="form-label fw-bold">Admin</label>
    <select name="admin" id="admin" class="form-select">
        <option value="0">Non</option>
        <option value="1">Oui</option>
    </select>

    <input type="submit" value="Ajouter le membre" class="btn btn-primary mt-2">

</form>

<?php
require_once(__DIR__ . "/../inc/adminFooter.php");
