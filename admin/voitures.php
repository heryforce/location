<?php

require_once(__DIR__ . "/inc/adminHeader.php");
require_once(__DIR__ . "/../inc/db_functions.php");

if ($_SESSION['admin'] == 0)
    header("location: ../index.php");

$voitures = getAllFromTable("voiture");
?>

<h2 class="text-center">SECTION DES VOITURES</h2>

<a href="index.php" class="btn btn-success">Retour à l'index du Backoffice</a>
<a href="add/addVoit.php" class="btn btn-warning">Nouvelle voiture</a>


<table class="table table-striped table-hover">
    <thead>
        <th>ID</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Lien de l'image</th>
        <th>Prix journalier</th>
        <th>Suppr/Edit</th>

    </thead>
    <tbody>
        <?php foreach ($voitures as $voiture) : ?>
            <tr>
                <td> <?= $voiture['id'] ?> </td>
                <td> <?= $voiture['nom'] ?> </td>
                <td> <?= $voiture['description'] ?> </td>
                <td> <img src="../public/img/<?= $voiture['image'] ?>" alt="<?= $voiture['image'] ?>" class="img-fluid"> </td>
                <td> <?= $voiture['prix_jour'] ?>€ </td>
                <td> <a href="del/delVoit.php?id=<?= $voiture['id'] ?>" class="btn btn-danger"> <i class="fas fa-trash"></i> </a>
                    <a href="edit/editVoit.php?id=<?= $voiture['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once(__DIR__ . "/inc/adminFooter.php");
