<?php

require_once(__DIR__ . "/../inc/adminHeader.php");
require_once(__DIR__ . "/../../inc/db_functions.php");

if (isset($_GET['id'])) {
    $voit = getVoit($_GET['id']);
    if (isset($_POST['nom']) && isset($_POST['description']) && $_POST['prix_jour']) {
        $err = "";
        if (iconv_strlen($_POST['nom']) < 5)
            $err .= "Le nom doit contenir au moins 5 caractères.<br>";
        if (iconv_strlen($_POST['description']) < 10)
            $err .= "La description doit contenir au moins 10 caractères.<br>";
        if ($_POST['prix_jour'] < 5)
            $err .= "Le prix journalier doit être d'au moins 5€.<br>";
        if (empty($err)) {
            $image = null;
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                if ($_FILES['image']['type'] == 'image/jpeg' || $_FILES['image']['type'] == 'image/png') {
                    $photoFileName = basename($_FILES['image']['name']);
                    $photoFileName = uniqid() . "-$photoFileName";
                    $destination = "../../public/img/$photoFileName";
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination) == true) {
                        $image = $photoFileName;
                        try {
                            $q = getPdo()->prepare("UPDATE voiture SET nom = ?, description = ?, image = ?, prix_jour = ? WHERE id = ?");
                            $q->execute(array($_POST['nom'], $_POST['description'], $image, $_POST['prix_jour'], $_GET['id']));
                        } catch (PDOException $e) {
                            printError("Erreur lors de l'insertion : " . $e->getMessage());
                        }
                    }
                }
            } else {
                try {
                    $q = getPdo()->prepare("UPDATE voiture SET nom = ?, description = ?, prix_jour = ? WHERE id = ?");
                    $q->execute(array($_POST['nom'], $_POST['description'], $_POST['prix_jour'], $_GET['id']));
                } catch (PDOException $e) {
                    printError("Erreur lors de l'insertion : " . $e->getMessage());
                }
            }
            header("location: ../voitures.php");
        }
    }
} else
    header("location: ../voitures.php");

if (!empty($err))
    printError($err);

?>

<a href="../voitures.php" class="btn btn-success mt-2">Retour à la liste</a>

<h2 class="text-center">Edition d'une voiture</h2>

<form method="post" enctype="multipart/form-data">

    <label for="nom" class="form-label fw-bold">Nom</label>
    <input id="nom" name="nom" type="text" class="form-control" value="<?= $voit['nom'] ?>" required>

    <label for="description" class="form-label fw-bold">Description</label>
    <input id="description" name="description" type="text" class="form-control" value="<?= $voit['description'] ?>" required>

    <label for="image" class="form-label fw-bold">Image</label>
    <input id="image" name="image" type="file" class="form-control">

    <label for="prix_jour" class="form-label fw-bold">Prix journalier</label>
    <input id="prix_jour" name="prix_jour" type="number" class="form-control" value="<?= $voit['prix_jour'] ?>" required>

    <input type="submit" value="Editer la voiture" class="btn btn-primary">

</form>

<?php
require_once(__DIR__ . "/../inc/adminFooter.php");
