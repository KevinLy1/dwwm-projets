<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? "Blog - " . $pageTitle : "Blog" ?></title>
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- CSS Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URLSITE ?>assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo URLSITE ?>">BLOG</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link <?php if($pageTitle == "Accueil") echo 'active' ?>" href="<?php echo URLSITE ?>">Accueil</a>
                        </li>

                        <?php if(!isConnected()) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if($pageTitle == "Inscription") echo 'active' ?>" href="<?php echo URLSITE ?>inscription.php">Inscription</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if($pageTitle == "Connexion") echo 'active' ?>" href="<?php echo URLSITE ?>connexion.php">Connexion</a>
                            </li>

                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if($pageTitle == "Ajout d'article") echo 'active' ?>" href="<?php echo URLSITE ?>form.php">Ajout d'article</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if($pageTitle == "Mes articles") echo 'active' ?>" href="<?php echo URLSITE ?>gestion.php">Mes articles</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php if($pageTitle == "Profil") echo 'active' ?>" href="<?php echo URLSITE ?>profil.php">Profil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo URLSITE ?>connexion.php?action=disconnect">Déconnexion (<?php echo $_SESSION["user"]["nom"] ?>)</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>