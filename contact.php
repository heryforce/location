<?php
require_once("inc/header.php");
require_once("inc/db_functions.php");

if (isset($_SESSION['msgOk'])) {
    printSuccess($_SESSION['msgOk']);
    unset($_SESSION['msgOk']);
}

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
        $_SESSION['msgOk'] = "Votre message a bien été envoyé !";
        header("location: contact.php");
    }
}

if (!empty($err))
    printError($err);
?>

<h3 class="text-center mt-3 fw-bold">Laissez-nous un message !</h3>

<div class="container mt-3">
    <form method="post">

        <label class="form-label fw-bold" for="email">Votre adresse mail</label>
        <input class="form-control" type="text" id="email" name="email" required>

        <label class="form-label fw-bold" for="contenu">Votre message</label>
        <textarea class="form-control" name="contenu" id="contenu" rows="10" required></textarea>

        <input class="btn btn-primary mt-2" type="submit" value="Envoyer">
    </form>
</div>

<?php
require_once("inc/footer.php");
