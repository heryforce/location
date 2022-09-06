<?php

require_once(__DIR__ . "/inc/adminHeader.php");
require_once(__DIR__ . "/../inc/db_functions.php");

if ($_SESSION['admin'] == 0)
    header("location: ../index.php");

$contacts = getAllFromTable("contact");
?>

<h2 class="text-center">SECTION DES PRISES DE CONTACT</h2>

<a href="index.php" class="btn btn-success">Retour à l'index du Backoffice</a>
<a href="add/addCont.php" class="btn btn-warning">Nouveau message de contact</a>

<table class="table table-striped table-hover">
    <thead>
        <th>ID</th>
        <th>Email</th>
        <th>Contenu</th>
        <th>Date et heure de l'envoi</th>
        <th>Suppr/Edit</th>

    </thead>
    <tbody>
        <?php foreach ($contacts as $contact) : ?>
            <tr>
                <td> <?= $contact['id'] ?> </td>
                <td> <?= $contact['email'] ?> </td>
                <td> <?= $contact['contenu'] ?> </td>
                <td> <?= date_format(date_create($contact['date_envoi']), "d/m/Y H:m:s") ?> </td>
                <td> <a href="del/delCont.php?id=<?= $contact['id'] ?>" class="btn btn-danger"> <i class="fas fa-trash"></i> </a>
                    <a href="edit/editCont.php?id=<?=$contact['id']?>" class="btn btn-warning"><i class="fas fa-edit"></i></a> </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once(__DIR__ . "/inc/adminFooter.php");
