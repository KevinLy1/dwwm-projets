<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Dynamiser le titre en fonction des pages -->
    <title><?= (isset($pageTitle) ? $pageTitle : "Todolist ECF2" ) ?></title>
    <!-- CDN Font-Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
            <div class="container">
                <a class="navbar-brand" href="./index.php">Todolist ECF Back-end</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" aria-current="page" href="./index.php">Ajout/Consultation</a></li>
                    <li class="nav-item"><a class="nav-link" href="./archives.php">Archives</a></li>
                </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <h1 class="mt-5">Todolist ECF Back-end</h1>