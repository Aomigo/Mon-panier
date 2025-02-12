<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Mon petit panier</title>

    <!-- Bootstrap Core CSS (Version 5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg bg-info-subtle mb-5" data-bs-theme="dark">
        <div class="container">
            <a href="<?php echo RACINE_SITE . 'index.php'; ?>" class="navbar-brand">MON PETIT PANIER</a>

            <!-- Bouton burger pour mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav1" aria-controls="nav1" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu de navigation -->
            <div class="collapse navbar-collapse" id="nav1">
                <ul class="navbar-nav ms-auto">
                    <?php
                    // Menu commun pour tous les visiteurs
                    echo '<li class="nav-item"><a class="nav-link" href="' . RACINE_SITE . 'index.php"><i class="fa-solid fa-magnifying-glass"></i> Recherche</a></li>';

                    // Menu utilisateurs connectés
                    if(internauteEstConnecte()) {
                        echo '<li class="nav-item"><a class="nav-link" href="' . RACINE_SITE . 'views/connexion.php?action=deconnexion"><i class="fa-solid fa-user"></i> Se déconnecter</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="' . RACINE_SITE . 'views/visu_panier.php"><i class="fa-solid fa-cart-shopping"></i> Panier</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="' . RACINE_SITE . 'views/commandes.php"><i class="fa-solid fa-folder"></i> Mes commandes</a></li>';

                    } else {
                        // Menu utilisateurs non connectés
                        echo '<li class="nav-item"><a class="nav-link" href="' . RACINE_SITE . 'views/inscription.php"><i class="fa-solid fa-user"></i> Inscription</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="' . RACINE_SITE . 'views/connexion.php"><i class="fa-solid fa-circle-user"></i> Connexion</a></li>';
                    }

                    // Menu admin
                    if (internauteEstConnecteEtAdmin()) {
                        echo '<li class="nav-item"><a class="nav-link" href="' . RACINE_SITE . 'admin/gestion_boutique.php"><i class="fa-solid fa-file-pen"></i> Gestion des recettes</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Contenu principal de la page -->
<div class="container" style="min-height: 80vh;">
    <div class="row">
        <div class="col-12">