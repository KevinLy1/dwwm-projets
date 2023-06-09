<?php 
require_once("./inc/init.php");
$pageTitle = "Inscription";

// Redirection vers la page profil si l'utilisateur est déjà connecté
if(isConnected()) {
    header("location:" . URLSITE . "profil.php");
    exit();
}

// Si le formulaire est posté
if(!empty($_POST)) {
    $erreurs = array();

    // Contrôle du nom d'utilisateur
    if(empty(trim($_POST["nom"]))) {
        $erreurs[] = "Merci de saisir votre nom d'utilisateur.";
    }
    else {
        if(getUserByName($_POST["nom"])) {
            $erreurs[] = "Ce nom est déjà utilisé. Merci d'en choisir un autre.";
        }
    }

    // Contrôle de l'e-mail
    if(empty(trim($_POST["email"])) ) {
        $erreurs[] = "Merci de saisir votre adresse e-mail.";
    }
    else {
        if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "Format d'adresse e-mail incorrect.";
        }

        if(getUserByEmail($_POST["email"])) {
            $erreurs[] = "Il existe déjà un compte avec cette adresse e-mail.";
        }
    }

    // Contrôle du mot de passe
    $pattern = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9_]{8,20}$#";
    if(empty(trim($_POST["mdp"]))) {
        $erreurs[] = "Merci de saisir un mot de passe.";
    }
    else {
        if(!preg_match($pattern, $_POST["mdp"])) {
            $erreurs[] = "Le mot de passe doit comporter entre 8 et 20 caractères et contenir au moins un minuscule, une majuscule et un chiffre.";
        }
    }

    if(empty($erreurs)) { // Si aucune erreur, procéder à l'insertion
        $_POST["mdp"] = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
        goSQL("INSERT INTO users (nom, email, mdp) VALUES (:nom, :email, :mdp)", $_POST);
        $_SESSION["message"] = "Inscription réussie. Vous pouvez vous connecter.";
        $_SESSION["typeMessage"] = "success";
        header("location:" . URLSITE . "connexion.php");
        exit();
    }
}

require_once("./inc/header.php");
?>

<div class="container">
    <div class="row row-cols-1 row-cols-lg-2 justify-content-center">
        <div class="col my-4">
            <h1>Inscription</h1>
            <hr>
            <?php if(!empty($erreurs)) : ?>
                <div class="alert alert-danger"><?php echo implode("<br>", $erreurs) ?></div>
            <?php endif; ?>
            <form method="post">                
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                    <input type="text" id="nom" name="nom" class="form-control" value="<?php echo $_POST["nom"] ?? "" ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $_POST["email"] ?? "" ?>">
                </div>

                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" id="mdp" name="mdp" class="form-control">
                        <span class="input-group-text" id="oeil"><i class="fa-solid fa-eye"></i></span>
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once("./inc/footer.php") ?>