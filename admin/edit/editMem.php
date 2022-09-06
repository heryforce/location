<?php

require_once(__DIR__ . "/../inc/adminHeader.php");
require_once(__DIR__ . "/../../inc/db_functions.php");

if (isset($_GET['id'])) {
    $mem = getMem($_GET['id']);
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['admin'])) {
        $err = "";

        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        if (iconv_strlen($_POST['mdp']) < 5)
            $err .= "Le mot de passe doit être composé d'au moins 5 caractères.<br>";

        if (iconv_strlen($_POST['username']) < 5)
            $err .= "Le nom d'utilisateur doit être composé d'au moins 5 caractères.<br>";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $err .= "Cette adresse mail n'est pas valide.<br>";

        if (empty($err)) {
            try {
                $mdp = password_hash($_POST['mdp'], PASSWORD_BCRYPT); // password_hash() hashe le mdp en paramètre avec le hashage demandé (ici, Bcrypt)
                $q = getPdo()->prepare("UPDATE membre SET email = ?, mdp = ?, username = ?, admin = ? WHERE id = ?");
                $q->execute(array($email, $mdp, $_POST['username'], $_POST['admin'], $_GET['id']));
            } catch (PDOException $e) {
                printError("Erreur lors de l'update : " . $e->getMessage());
            }
            header("location: ../membres.php");
        }
    }
} else
    header("location: ../membres.php");

if (!empty($err)) {
    printError($err); // s'il y a eu au moins une erreur, je l'affiche
}

?>

<a href="../membres.php" class="btn btn-success mt-2">Retour à la liste</a>

<h2 class="text-center">Edition d'un membre</h2>

<form method="post">

    <label for="username" class="form-label fw-bold">Nom d'utilisateur</label>
    <input type="text" name="username" id="username" class="form-control" value="<?= $mem['username'] ?>" required>

    <label for="email" class="form-label fw-bold">Adresse mail</label>
    <input type="text" name="email" id="email" class="form-control" value="<?= $mem['email'] ?>" required>

    <label for="mdp" class="form-label fw-bold">Mot de passe</label>
    <input type="password" name="mdp" id="mdp" class="form-control" required>
    <div class="form-text  ">Il doit être composé d'au moins 5 caractères</div>

    <label for="admin" class="form-label fw-bold">Admin</label>
    <select class="form-select" name="admin" id="admin">
        <?php if ($mem['admin'] == 0) : ?>
            <option selected value="0">Non</option>
        <?php else : ?>
            <option value="0">Non</option>
        <?php endif; ?>
        <?php if ($mem['admin'] == 1) : ?>
            <option selected value="1">Oui</option>
        <?php else : ?>
            <option value="1">Oui</option>
        <?php endif ?>
    </select>

    <input type="submit" value="Editer le membre" class="btn btn-primary mt-2">

</form>

<?php
require_once(__DIR__ . "/../inc/adminFooter.php");
