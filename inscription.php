<?php
require_once("inc/header.php");
require_once("inc/db_functions.php");

if (!empty($_POST['email']) && !empty($_POST['mdp']) && !empty($_POST['username'])) {

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
            inscriptionMem($email, $_POST['mdp'], $_POST['username']);
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
        }
        $_SESSION['inscriptionOk'] = "Votre compte a bien été crée, vous pouvez maintenant vous connecter !";
        header("location: connexion.php");
    }
}

if (!empty($err)) {
    printError($err); // s'il y a eu au moins une erreur, je l'affiche
}
?>


<h3 class="text-center mt-3 fw-bold">Création de votre compte !</h3>

<div class="container mt-3">
    <form method="post">

        <label for="username" class="form-label fw-bold">Votre nom d'utilisateur</label>
        <input type="text" name="username" id="username" class="form-control" required>

        <label for="email" class="form-label fw-bold">Votre adresse mail</label>
        <input type="text" name="email" id="email" class="form-control" required>

        <label for="mdp" class="form-label fw-bold">Votre mot de passe</label>
        <input type="password" name="mdp" id="mdp" class="form-control" required>
        <div class="form-text  ">Il doit être composé d'au moins 5 caractères</div>

        <input type="submit" value="Créer un compte" class="btn btn-primary mt-2">

    </form>
</div>

<?php
require_once("inc/footer.php");
