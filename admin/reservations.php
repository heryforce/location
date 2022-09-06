<?php

require_once(__DIR__ . "/inc/adminHeader.php");
require_once(__DIR__ . "/../inc/db_functions.php");

if ($_SESSION['admin'] == 0)
    header("location: ../index.php");

$resas = getAllFromTable("reservation");
?>

<h2 class="text-center">SECTION DES RESERVATIONS</h2>

<a href="index.php" class="btn btn-success">Retour à l'index du Backoffice</a>
<a href="add/addResa.php" class="btn btn-warning">Nouvelle réservation</a>


<table class="table table-striped table-hover">
    <thead>
        <th>ID</th>
        <th>ID du membre</th>
        <th>ID de la voiture</th>
        <th>Date de début</th>
        <th>Date de fin</th>
        <th>Prix</th>
        <th>Suppr/Edit</th>
    </thead>
    <tbody>
        <?php foreach ($resas as $resa) : ?>
            <tr>
                <td> <?= $resa['id'] ?> </td>
                <td> <?= $resa['id_membre'] . " (" . getMem($resa['id_membre'])['username'] . ")" ?> </td>
                <td> <?= $resa['id_voiture'] . " (" . getVoit($resa['id_voiture'])['nom'] . ")" ?> </td>
                <td> <?= date_format(date_create($resa['date_debut']), "d/m/Y") ?> </td>
                <td> <?= date_format(date_create($resa['date_fin']), "d/m/Y") ?> </td>
                <td> <?= $resa['prix'] ?>€ </td>
                <td> <a href="del/delResa.php?id=<?= $resa['id'] ?>" class="btn btn-danger"> <i class="fas fa-trash"></i> </a>
                    <a href="edit/editResa.php?id=<?= $resa['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once(__DIR__ . "/inc/adminFooter.php");
