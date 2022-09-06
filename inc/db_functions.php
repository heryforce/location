<?php

function getPdo()
{
    $pdo = new PDO("mysql:host=localhost;dbname=location", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    return $pdo;
}

/**
 * Cette fonction affiche la string passée en paramètre dans une div rouge
 * 
 * @param string $str La string à afficher
 */

function printError(string $str)
{
    echo "<div class='alert alert-danger'>$str</div>";
}

/**
 * Cette fonction affiche la string passée en paramètre dans une div verte
 * 
 * @param string $str La string à afficher
 */

function printSuccess(string $str)
{
    echo "<div class='alert alert-success'>$str</div>";
}

/**
 * Cette fonction est utilisée de deux manières différentes :  
 * - lors de l'inscription, la fonction ne reçoit pas de mot de passe et vérifie si un compte existe déjà avec l'email ou le username passé en paramètre ;  
 * - lors de la connexion, la fonction reçoit un mot de passe et vérifie si le mot de passe correspond au compte grâce à **password_verify()**
 * 
 * @param string $email L'email du compte
 * @param mixed $username Le nom d'utilisateur recherché dans la BDD
 * @param string $mdp Le mot de passe rentré lors de la connexion
 * @return bool  - Lors de l'inscription, renvoie TRUE si un compte existe déjà avec l'email ou le username passé en paramètre, sinon renvoie FALSE  
 * - Lors de la connexion, renvoie le retour de **password_verify()**
 */

function isKnown(string $email, $username, string $mdp = NULL)
{
    $q = getPdo()->prepare("SELECT * FROM membre WHERE email = ? OR username = ?");
    $q->execute(array($email, $username));
    $data = $q->fetch();

    if (is_null($mdp)) {
        if ($data != NULL)
            return TRUE;
        else
            return FALSE;
    } else {
        if ($data != NULL)
            return password_verify($mdp, $data['mdp']); // password_verify() est une fonction prédéfinie permettant de vérifier si le mdp brut correspond au mdp hashé grâce à password_hash() (le mot de passe est hashé dans la fonction inscriptionMem() un peu plus bas)
    }
}

/**
 * Cette fonction vérifie si une réservation est possible via les dates indiquées par l'utilisateur mais est utilisée de deux manières différentes :  
 * - si _id_membre_ est null, alors c'est une vérification de réservation depuis le front-office : si la date de début de reservation se situe entre deux dates de réservation ou si la
 *  date de fin de réservation se situe entre deux dates de réservation alors je ne peux pas réserver aux dates choisies  
 * - si _id_membre_ n'est pas null, c'est une édition de réservation depuis le back-office : même chose avec indication du membre en plus
 * 
 * @param string $date_debut La date de début de location
 * @param string $date_fin La date de fin de location
 * @param int $id_voiture L'id de la voiture
 * @param int $id_membre L'id du membre
 */

function isResaValid(string $date_debut, string $date_fin, int $id_voiture, int $id_membre = NULL)
{
    $date = date("Y-m-d");
    if ($date > $date_debut || $date > $date_fin || $date_fin < $date_debut)
        return FALSE;
    $q = getPdo()->prepare("SELECT * FROM reservation
    WHERE id_voiture = ? 
    AND ((date_debut <= ? AND date_fin >= ?)
    OR  (date_debut <= ? AND date_fin >= ?))
    ");

    $q->execute(array($id_voiture, $date_debut, $date_debut, $date_fin, $date_fin));
    $data = $q->fetchAll(PDO::FETCH_ASSOC);

    if (is_null($id_membre)) {
        if ($data)
            return FALSE;
        else
            return TRUE;
    } else {
        foreach ($data as $resa) {
            if ($resa['id_membre'] != $id_membre)
                return FALSE;
        }
        return TRUE;
    }
}

/**
 * Cette fonction permet de récupérer le nombre de jours entre deux dates pour pouvoir ensuite calculer le prix de la réservation
 * 
 * @param string $date_debut La date de début de location
 * @param string $date_fin La date de fin de location
 * @return array Renvoie l'enregistrement trouvé en BDD ou NULL
 */

function getDateDiff(string $date_debut, string $date_fin)
{
    $q = getPdo()->query("SELECT DATEDIFF('$date_fin', '$date_debut')");
    return $q->fetch();
}

/**
 * Cette fonction permet de récupérer toutes les réservations faites par un membre via l'id du membre
 * 
 * @param int $id L'id du membre
 * @return array Renvoie les enregistrements trouvés en BDD ou NULL
 */

function getResaViaMembre(int $id)
{
    $q = getPdo()->query("SELECT * FROM reservation WHERE id_membre = $id ORDER BY date_debut ASC");
    return $q->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Cette fonction permet de récupérer toutes les réservations faites pour une voiture via l'id de la voiture
 * 
 * @param int $id L'id de la voiture
 * @return array Renvoie les enregistrements trouvés en BDD ou NULL
 */

function getResaViaVoiture(int $id)
{
    $q = getPdo()->query("SELECT * FROM reservation WHERE id_voiture = $id");
    return $q->fetchAll(PDO::FETCH_ASSOC);
}

function editResa(int $id_voiture, string $date_debut, string $date_fin, int $prix, int $id, int $id_membre)
{
    $q = getPdo()->prepare("UPDATE reservation SET id_membre = ?, id_voiture = ?, date_debut = ?, date_fin = ?, prix = ? WHERE id = ?");
    return $q->execute(array($id_membre, $id_voiture, $date_debut, $date_fin, $prix, $id));
}


// ****************** INSERER DES DONNEES DANS UNE TABLE **********************

/**
 * Cette fonction permet d'insérer une réservation en BDD :  
 * - Si id_membre est NULL, c'est une insertion lors d'une réservation dans le front-office : l'id_membre utilisé sera celui de 
 * l'utilisateur connecté  
 * - Si id_membre n'est pas NULL, c'est une insertion lors d'une réservation dans le back-office : l'id_membre utilisé est précisé 
 * lors de la réservation
 * 
 * @param int $id_voiture L'id de la voiture
 * @param string $date_debut La date de début de réservation
 * @param string $date_fin La date de fin de réservation
 * @param int $prix Le prix total de la réservation
 * @param int $id_membre L'id du membre
 * @return int Renvoie le nombre d'insertion (1 ou 0)
 */

function validateResa(int $id_voiture, string $date_debut, string $date_fin, int $prix, int $id_membre = NULL)
{
    $q = getPdo()->prepare("INSERT INTO reservation (id_membre, id_voiture, date_debut, date_fin, prix) VALUES (?, ?, ?, ?, ?)");
    if (is_null($id_membre))
        return $q->execute(array($_SESSION['id'], $id_voiture, $date_debut, $date_fin, $prix));
    else
        return $q->execute(array($id_membre, $id_voiture, $date_debut, $date_fin, $prix));
}

function inscriptionMem(string $email, string $mdp, string $username)
{
    $mdp = password_hash($_POST['mdp'], PASSWORD_BCRYPT); // password_hash() hashe le mdp en paramètre avec le hashage demandé (ici, Bcrypt)
    $q = getPdo()->prepare("INSERT INTO membre (email, mdp, username) VALUES (?, ?, ?)");
    $q->execute(array($email, $mdp, $username));
}


// ****************** SUPPRIMER UN ENREGISTREMENT D'UNE TABLE **********************


function delCont(int $id)
{
    return getPdo()->exec("DELETE FROM contact WHERE id = $id");
}

function delMem(int $id) // supprimer un membre implique aussi de supprimer toutes les réservations de ce membre
{
    $tab = getResaViaMembre($id);
    foreach ($tab as $resa)
        delResa($resa['id']);
    return getPdo()->exec("DELETE FROM membre WHERE id = $id");
}

function delResa(int $id)
{
    return getPdo()->exec("DELETE FROM reservation WHERE id = $id");
}

function delVoit(int $id) // supprimer une voiture implique aussi de supprimer toutes les locations de cette voiture
{
    $tab = getResaViaVoiture($id);
    foreach ($tab as $resa)
        delResa($resa['id']);
    unlink("../../public/img/" . getVoit($id)['image']); // supprime l'image de la voiture
    return getPdo()->exec("DELETE FROM voiture WHERE id = $id");
}


// ****************** RECUPERER UN ENREGISTREMENT D'UNE TABLE **********************


function getVoit(int $id)
{
    $q = getPdo()->prepare("SELECT * FROM voiture WHERE id = ?");
    $q->execute(array($id));
    return $q->fetch(PDO::FETCH_ASSOC);
}

function getCont(int $id)
{
    $q = getPdo()->query("SELECT * FROM contact WHERE id = $id");
    return $q->fetch(PDO::FETCH_ASSOC);
}

function getResa(int $id)
{
    $q = getPdo()->query("SELECT * FROM reservation WHERE id = $id");
    return $q->fetch(PDO::FETCH_ASSOC);
}

function getMem(int $id)
{
    $q = getPdo()->query("SELECT * FROM membre WHERE id = $id");
    return $q->fetch(PDO::FETCH_ASSOC);
}


// ****************** RECUPERER TOUS LES ENREGISTREMENTS DE CHAQUE TABLE **********************

function getAllFromTable($table)
{
    $q = getPdo()->query("SELECT * FROM $table");
    return $q->fetchAll(PDO::FETCH_ASSOC);
}
