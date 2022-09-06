<?php
require_once("inc/header.php");
require_once("inc/db_functions.php");

if (!$_SESSION['id'])
    header('location: connexion.php');

$voiture = getVoit($_GET['id']);

if (!isResaValid($_GET['date_debut'], $_GET['date_fin'], $_GET['id'])) {
    $_SESSION['erreur_resa'] = "Cette voiture ne peut pas être réservée à ces dates. Veuillez choisir un autre intervalle.";
    header("location: resaVehicule.php?id=" . $_GET['id']);
} else if (!$voiture) 
{
    $_SESSION['pasDeVoiture'] = "Cette voiture n'existe pas. Veuillez choisir une voiture existante.";
    header("location: index.php");
}

$diff = getDateDiff($_GET['date_debut'], $_GET['date_fin']); // je récupère le nombre de jours de location sous forme de string
$diff = (int) $diff[0]; // je transforme la string en int

$total = ($diff * $voiture['prix_jour']) + $voiture['prix_jour'];

?>

<div class="container text-center p-2">

    <h2 class="fw-bold">Veuillez confirmer votre réservation :</h2>

    <div class="col-md-12 border rounded mr-1 mb-5 text-center  ">
        <h3 class="  fw-bold"> <?= $voiture['nom'] ?> </h3><br>
        <img src="public/img/<?= $voiture['image'] ?>"> <br>
        <span class="fw-bold"><?= "Date de début : " . date_format(date_create($_GET['date_debut']), "d/m/Y") . '<br>' ?></span>
        <span class="fw-bold"><?= "Date de retour : " . date_format(date_create($_GET['date_fin']), "d/m/Y") . '<br>' ?></span>
        <span class="fw-bold"><?= "Prix total : " . $total . "€" ?></span>

        <form action="confirmation.php" method="post">
            <!-- l'utilisateur ne verra qu'un bouton "Confirmer" : c'est en fait un formulaire POST dont tous les champs sont cachés -->
            <!-- cela permet d'envoyer des données en POST à la page confirmation.php sans que l'utilisateur ne rentre quoi que ce soit -->
            <!-- nous pouvons aussi inscrire ces données en session puis les détruire lors de la confirmation -->
            <input type="hidden" name="idv" value="<?= $voiture['id'] ?>">
            <input type="hidden" name="dd" value="<?= $_GET['date_debut'] ?>">
            <input type="hidden" name="df" value="<?= $_GET['date_fin'] ?>">
            <input type="hidden" name="p" value="<?= $total ?>">
            <input class="btn btn-primary btn-lg w-100 my-2 fw-bold" type="submit" value="Confirmer">
        </form>
    </div>

</div>

<?php
require_once("inc/footer.php");
