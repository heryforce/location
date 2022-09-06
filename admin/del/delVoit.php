<?php

require_once(__DIR__ . "/../../inc/db_functions.php");

if (isset($_GET['id'])) {
    if (!delVoit($_GET['id'])) {
        echo "Erreur durant la suppression";
        die;
    } else
        header("location: ../voitures.php");
}
