<?php

require_once(__DIR__ . "/../inc/adminHeader.php");
require_once(__DIR__ . "/../../inc/db_functions.php");

if ($_GET['id']) {
    $resa = getResa($_GET['id']);
    $mems = getAllFromTable("membre");
    $voits = getAllFromTable("voiture");
    if (isset($_POST['id_membre']) && isset($_POST['id_voiture']) && isset($_POST['date_debut']) && isset($_POST['date_fin'])) {
        $voiture = getVoit($_POST['id_voiture']);

        $diff = getDateDiff($_POST['date_debut'], $_POST['date_fin']);
        $diff = (int) $diff[0];
        $total = ($diff * $voiture['prix_jour']) + $voiture['prix_jour'];

        $err = "";
        if (!isResaValid($_POST['date_debut'], $_POST['date_fin'], $_POST['id_voiture'], $_POST['id_membre']) || !editResa($_POST['id_voiture'], $_POST['date_debut'], $_POST['date_fin'], $total, $_GET['id'], $_POST['id_membre'])) {
            $err .= "Cette voiture ne peut pas être réservée à ces dates. Veuillez choisir un autre intervalle.<br>";
        } else
            header("location: ../reservations.php");
    }
} else
    header("location: ../reservations.php");

if (!empty($err))
    printError($err);

?>

<a href="../reservations.php" class="btn btn-success mt-2">Retour à la liste</a>

<h2 class="text-center">Edition d'une réservation</h2>

<form method="post">

    <label for="id_membre" class="form-label fw-bold">Membre</label>
    <select name="id_membre" id="id_membre" class="form-select">
        <?php foreach ($mems as $mem) : ?>
            <!-- ce if sert à afficher par défaut le membre qui a passé la réservation -->
            <?php if ($resa['id_membre'] == $mem['id']) : ?>
                <option selected value="<?= $mem['id'] ?>"><?= $mem['username'] ?></option>
            <?php else : ?>
                <option value="<?= $mem['id'] ?>"><?= $mem['username'] ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>

    <label for="id_voiture" class="form-label fw-bold">Voiture</label>
    <select name="id_voiture" id="id_voiture" class="form-select">
        <?php foreach ($voits as $voit) : ?>
            <!-- ce if sert à afficher par défaut la voiture réservée -->
            <?php if ($resa['id_voiture'] == $voit['id']) : ?>
                <option selected value="<?= $voit['id'] ?>"><?= $voit['nom'] ?></option>
            <?php else : ?>
                <option value="<?= $voit['id'] ?>"><?= $voit['nom'] ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>

    <label class="form-label  " for="date_debut">Date de début</label>
    <input class="form-control" type="date" name="date_debut" id="date_debut" value="<?= date("Y-m-d") ?>" min="<?= date("Y-m-d") ?>">

    <label class="form-label  " for="date_fin">Date de fin</label>
    <input class="form-control" type="date" name="date_fin" id="date_fin" value="<?= $resa['date_fin'] ?>" min="<?= date("Y-m-d") ?>">

    <input type="submit" value="Editer la réservation" class="btn btn-primary mt-2">
</form>

<?php
require_once(__DIR__ . "/../inc/adminFooter.php");
