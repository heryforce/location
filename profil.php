<?php
require_once("inc/header.php");
require_once("inc/db_functions.php");

if (empty($_SESSION['id']))
    header("location: connexion.php");

$infos = getMem($_SESSION['id']);
$resas = getResaViaMembre($_SESSION['id']);

if (empty($resas))
    echo "<h2 class='text-center fw-bold'>Vous n'avez encore jamais réservé de voiture !";
else {
?>
    <table class="table table-striped table-hover">
        <thead>
            <th>N° de réservation</th>
            <th>Voiture</th>
            <th></th>
            <th>Date de début</th>
            <th>Date de fin</th>
            <th>Prix</th>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < count($resas); $i++) {
                echo "<tr>";
                echo "<td>" . $resas[$i]['id'] . "</td>";
                echo "<td class='fw-bold'>" . getVoit($resas[$i]['id_voiture'])['nom'] . "</td>";
                echo "<td><img src='public/img/" . getVoit($resas[$i]['id_voiture'])['image'] . "' style='width: 150px;'></td>";
                echo "<td class='fw-bold'>" . date_format(date_create($resas[$i]['date_debut']), "d/m/Y") . "</td>";
                echo "<td class='fw-bold'>" . date_format(date_create($resas[$i]['date_fin']), "d/m/Y") . "</td>";
                echo "<td class='fw-bold'>" . $resas[$i]['prix'] . "€</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
<?php
}
require_once("inc/footer.php");
echo $_SESSION['admin'];
