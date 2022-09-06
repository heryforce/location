<?php

require_once("inc/header.php");
require_once("inc/db_functions.php");

$demain = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));

if (!$_SESSION['id'])
    header('location: connexion.php');

$voiture = getVoit($_GET['id']);

if (!isset($_GET['id']) || $voiture == NULL) {
    // s'il y a une erreur, je stocke un msg d'erreur en session
    $_SESSION['pasDeVoiture'] = "Cette voiture n'existe pas. Veuillez choisir une voiture existante.";
    header("location: index.php");
}

if (isset($_SESSION['erreur_resa'])) // s'il y a un msg d'erreur en session, je l'affiche puis je le supprime
{
    printError($_SESSION['erreur_resa']);
    unset($_SESSION['erreur_resa']);
}
$_SESSION['voiture_id'] = $_GET['id'];
$resas = getResaViaVoiture($_GET['id']);

?>
<div class="row">
    <div class="col-lg-2 col-md-4 col-sm-6 mt-3 mx-auto">
        <div class="card bg-transparent" style="width: 18rem;">
            <img src="public/img/<?= $voiture['image'] ?>" class="card-img-top mx-auto" alt="Image de voiture" style="width: 230px;">
            <div class="card-body">
                <h5 class="card-title  "><?= $voiture['nom'] ?></h5>
                <p class="card-text  "><?= $voiture['description'] ?>
                    <br><br> Prix par jour : <?= $voiture['prix_jour'] ?>€
                </p>

                <form method="get" action="verifResa.php">
                    <label class="form-label  " for="date_debut">Date de début</label>
                    <input class="form-control" type="date" name="date_debut" id="date_debut" value="<?= date("Y-m-d") ?>" min="<?= date("Y-m-d") ?>">

                    <label class="form-label  " for="date_fin">Date de fin</label>
                    <input class="form-control" type="date" name="date_fin" id="date_fin" value="<?= $demain ?>" min="<?= date("Y-m-d") ?>">

                    <input type="hidden" name="id" value="<?= $voiture['id'] ?>">

                    <input class="btn btn-primary mt-2" type="submit" value="Vérifier et réserver">
                </form>
            </div>
        </div>
    </div>
    <!-- je crée une deuxième colonne seulement si le véhicule est déjà réservé pour afficher les dates où il n'est pas disponible -->
    <?php if ($resas) : ?>
        <div class="col-lg-2 col-md-4 col-sm-6 mt-3 mx-auto">
            <table class="table">
                <tbody>
                    <?php foreach ($resas as $resa) : ?>
                        <tr>
                            <td class="text-danger fw-bold">Déjà réservé du <?= date_format(date_create($resa['date_debut']), "d/m/Y") ?> au <?= date_format(date_create($resa['date_fin']), "d/m/Y") ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php

require_once("inc/footer.php");
