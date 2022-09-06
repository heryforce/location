<?php
require_once("inc/header.php");
require_once("inc/db_functions.php");

$voitures = getAllFromTable("voiture");

if (isset($_SESSION['pasDeVoiture'])) // s'il y a un msg d'erreur en session, je l'affiche puis je le supprime
{
    printError($_SESSION['pasDeVoiture']);
    unset($_SESSION['pasDeVoiture']);
}

?>

<div class="row">
    <?php
    foreach ($voitures as $voiture) :
    ?>
        <div class="col-lg-4 col-md-6 col-sm-8 mt-3">
            <div class="card bg-transparent" style="width: 18rem;">
                <img src="public/img/<?= $voiture['image'] ?>" class="card-img-top mx-auto" alt="Image de voiture" style="width: 230px;">
                <div class="card-body">
                    <h5 class="card-title  "><?= $voiture['nom'] ?></h5>
                    <p class="card-text  "><?= substr($voiture['description'], 0, 30) . '...' ?></p>
                    <a href="resaVehicule.php?id=<?= $voiture['id'] ?>" class="btn btn-primary">Réserver cette voiture</a>
                </div>
            </div>
        </div>
    <?php
    endforeach;
    ?>
</div>

<?php
require_once("inc/footer.php");
