<?php
require_once(__DIR__ . "/inc/adminHeader.php");

if ($_SESSION['admin'] == 0)
    header("location: ../index.php");

?>

<h2 class="text-center">BACKOFFICE</h2>


<div class="container">
    <a href="membres.php" class="col-md-6 offset-md-3 btn btn-dark p-3 mb-2">SECTION DES MEMBRES</a>
    <a href="voitures.php" class="col-md-6 offset-md-3 btn btn-dark p-3 mb-2">SECTION DES VOITURES</a>
    <a href="reservations.php" class="col-md-6 offset-md-3 btn btn-dark p-3 mb-2">SECTION DES RESERVATIONS</a>
    <a href="contact.php" class="col-md-6 offset-md-3 btn btn-dark p-3 mb-2">SECTION DES PRISES DE CONTACT</a>
    <a href="../index.php" class="col-md-6 offset-md-3 btn btn-success p-3 mb-2">Retour au site</a>
</div>

<?php
require_once(__DIR__ . "/inc/adminFooter.php");
