<?php

require_once(__DIR__ . "/../inc/adminHeader.php");
require_once(__DIR__ . "/../../inc/db_functions.php");

if (isset($_POST['email']) && isset($_POST['contenu'])) {
    $err = "";

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $err .= "Cette adresse mail n'est pas valide.<br>";

    if (iconv_strlen($_POST['contenu']) < 10)
        $err .= "Votre message doit contenir au moins 10 caractères.<br>";

    if (empty($err)) {
        $q = getPdo()->prepare("INSERT INTO contact (email, contenu, date_envoi) VALUES (?, ?, NOW())");
        $q->execute(array($email, $_POST['contenu']));
        header("location: ../contact.php");
    }
}

if (!empty($err))
    printError($err);

?>

<a href="../contact.php" class="btn btn-success mt-2">Retour à la liste</a>

<h2 class="text-center">Ajout d'un message de contact</h2>

<form method="post">

    <label class="form-label fw-bold" for="email">Adresse mail</label>
    <input class="form-control" type="text" id="email" name="email" required>

    <label class="form-label fw-bold" for="contenu">Message</label>
    <textarea class="form-control" name="contenu" id="contenu" rows="10" required></textarea>

    <input class="btn btn-primary mt-2" type="submit" value="Envoyer">
</form>

<?php
require_once(__DIR__ . "/../inc/adminFooter.php");
