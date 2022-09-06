<?php

require_once(__DIR__ . "/inc/adminHeader.php");
require_once(__DIR__ . "/../inc/db_functions.php");

if ($_SESSION['admin'] == 0)
    header("location: ../index.php");

$membres = getAllFromTable("membre");

?>

<h2 class="text-center">SECTION DES MEMBRES</h2>

<a href="index.php" class="btn btn-success">Retour à l'index du Backoffice</a>
<a href="add/addMem.php" class="btn btn-warning">Nouveau membre</a>

<table class="table table-striped table-hover">
    <thead>
        <th>ID</th>
        <th>Email</th>
        <th>Nom d'utilisateur</th>
        <th>Admin ?</th>
        <th>Suppr/Edit</th>

    </thead>
    <tbody>
        <?php foreach ($membres as $membre) : ?>
            <tr>
                <td> <?= $membre['id'] ?> </td>
                <td> <?= $membre['email'] ?> </td>
                <td> <?= $membre['username'] ?> </td>
                <td> <?php if($membre['admin'] == 0) echo "Non"; else echo "Oui"; ?> </td>
                <td> <a href="del/delMem.php?id=<?= $membre['id'] ?>" class="btn btn-danger"> <i class="fas fa-trash"></i> </a>
                <a href="edit/editMem.php?id=<?=$membre['id']?>" class="btn btn-warning"><i class="fas fa-edit"></i></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once(__DIR__ . "/inc/adminFooter.php");
