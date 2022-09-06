<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="public/img/favicone.ico" type="image/ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="public/css/style.css">
    <title>Location de voiture</title>

</head>

<body class="d-flex flex-column h-100">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><span class="fw-bold">LOCATION DE VOITURE</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <?php
                        session_start();
                        if (!isset($_SESSION['id'])) :
                        ?>
                            <a class="nav-link active" href="connexion.php"><span class="fw-bold">Se connecter</span></a>
                            <a class="nav-link active" href="inscription.php"><span class="fw-bold">S'inscrire</span></a>
                        <?php
                        else :
                        ?>
                            <a class="nav-link active" href="profil.php"><span class="fw-bold">Profil</span></a>
                            <a class="nav-link active" href="deconnexion.php"><span class="fw-bold">Se déconnecter</span></a>
                        <?php
                        endif;
                        ?>
                        <a class="nav-link active" href="contact.php"><span class="fw-bold">Contactez-nous</span></a>
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) : ?>
                            <a class="nav-link active" href="admin/index.php"><span class="fw-bold">ADMINISTRATION</span></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['id'])) :
                ?>
                    <span class="fw-bold">Bonjour <?= $_SESSION['username'] ?></span>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">