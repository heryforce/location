<?php
require_once("inc/header.php");
require_once("inc/db_functions.php");

if (
  isset($_POST['idv']) &&
  isset($_POST['dd']) &&
  isset($_POST['df']) &&
  isset($_POST['p'])
) {
  if (!isResaValid($_POST['dd'], $_POST['df'], $_POST['idv']) || !validateResa($_POST['idv'], $_POST['dd'], $_POST['df'], $_POST['p'])) {
    // s'il y a une erreur, je stocke un msg d'erreur en session puis je redirige vers resaVehicule.php où mon msg d'erreur sera affiché

    $_SESSION['erreur_resa'] = "Cette voiture ne peut pas être réservé à ces dates. Veuillez choisir un autre intervalle.";
    header("location: resaVehicule.php?id=" . $_POST['idv']);
  }
} else // s'il manque des données au formulaire POST, je redirige vers l'index
  header("location: index.php");

unset($_SESSION['voiture_id']);
?>

<div class="container-fluid py-5">
  <h1 class="display-5 fw-bold">Merci !</h1>
  <p class="col-md-8 fs-4 fw-bold">Votre réservation a été enregistrée ! Vous pouvez dès à présent y accéder depuis votre profil.</p>
  <a href="index.php" class="btn btn-primary btn-lg fw-bold">Retourner à l'accueil</a>
</div>

<?php
require_once("inc/footer.php");
