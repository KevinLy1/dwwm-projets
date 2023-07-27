<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD Entreprise</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">RH Entreprise</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?op=add">Ajouter</a>
                    </li>
                </ul>
                <form class="d-flex" role="search" method="GET">
                    <input type="hidden" name="op" value="search">    
                    <input class="form-control me-2" type="search" name="name" placeholder="Trouver un employé" aria-label="Search">
                    <button class="btn btn-success" type="submit">Rechercher</button>
                </form>
            </div>
        </div>
    </nav>

    <h1 class="text-center my-5"><?php echo $title; ?></h1>
    <div class="container">
        <div class="alert alert-info text-center">
            💬 <?php echo $message ?>
        </div>
        <?php if(!empty($alert)) {
            echo "<div class='alert alert-warning text-center'>$alert</div>";
        } ?>
    </div>

    <div class="container my-5" style="min-height: 79vh;">
        <?php echo $content ?>
    </div>


    <footer class="container-fluid navbar-dark bg-dark text-center" style="min-height: 60px; color: white">
        <p style="padding: 15px;"><?= date('Y') ?> - Tous droits réservés - <i class="fa-solid fa-copyright"></i> RH Entreprise</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>