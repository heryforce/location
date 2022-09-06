<?php

require_once(__DIR__ . "/../../inc/db_functions.php");

if (isset($_GET['id']))
{
    if (!delMem($_GET['id'])) {
        echo "Erreur durant la suppression";
        die;
    }
    else
    header("location: ../membres.php");
}