<?php
require_once("inc/header.php");
require_once("inc/db_functions.php");

$err = "";

if (isset($_SESSION['inscriptionOk'])) {
    printSuccess($_SESSION['inscriptionOk']);
    unset($_SESSION['inscriptionOk']);
}

if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!isKnown($email, null, $_POST['mdp']))
        $err .= "Adresse mail ou mot de passe incorrect.<br>";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $err .= "Cette adresse mail n'est pas valide.<br>";

    if (empty($err)) {
        try {
            $q = getPdo()->query("SELECT * FROM membre WHERE email = '$email'");
        } catch (PDOException $e) {
            printError("Erreur lors de la connexion : " . $e->getMessage());
        }
        $data = $q->fetch(PDO::FETCH_ASSOC);
        // je sauvegarde en session les données dont j'ai besoin
        // l'id pour la réservation, username pour le message perso dans la barre de navigation, le role admin pour l'accès au backoffice
        $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['admin'] = $data['admin'];
        header("location: profil.php");
    }
}

if (!empty($err))
    printError($err);

?>

<h3 class="text-center mt-3 fw-bold">Connexion !</h3>

<div class="container mt-3">
    <form method="post">

        <label for="email" class="form-label fw-bold">Votre adresse mail</label>
        <input type="text" name="email" id="email" class="form-control">

        <label for="mdp" class="form-label fw-bold">Votre mot de passe</label>
        <input type="password" name="mdp" id="mdp" class="form-control">

        <input type="submit" value="Se connecter" class="btn btn-primary mt-2">

    </form>
</div>
<?php
require_once("inc/footer.php");
