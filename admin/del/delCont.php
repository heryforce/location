<?php

require_once(__DIR__ . "/../../inc/db_functions.php");

if (isset($_GET['id'])) {
    if (delCont($_GET['id']) == FALSE) {
        echo "Erreur durant la suppression";
        die;
    } else
        header("location: ../contact.php");
}
