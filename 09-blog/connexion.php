<?php
require_once("./inc/init.php");
$pageTitle = "Connexion";

// Gérer la déconnexion
if(isset($_GET["action"]) && $_GET["action"] == "disconnect") {
    unset($_SESSION["user"]); // destruction de variable
    // session_destroy();
    $_SESSION["message"] = "Vous êtes maintenant déconnecté(e).";
    $_SESSION["typeMessage"] = "info";

    header("location:" . URLSITE);
    exit();
}

// Redirection vers la page profil si l'utilisateur est connecté
if(isConnected()) {
    header("location:" . URLSITE . "profil.php");
    exit();
}

if(!empty($_POST)) {
    $erreurs = array();

    if(empty(trim($_POST["nom"])) || empty(trim($_POST["mdp"]))) {
        $erreurs[] = "Merci de saisir les deux champs";
    }

    if(empty($erreurs)) {
        $user = getUserByName($_POST["nom"]);
        if(!$user) {
            $erreurs[] = "Erreur sur les identifiants";
        }
        else {
            if(!password_verify($_POST["mdp"], $user["mdp"])) {
                $erreurs[] = "Erreur sur les identifiants";
            }
            else {
                // Connnexion de l'utilisateur
                $_SESSION["user"] = $user;
                $_SESSION["message"] = "Connexion réussie. Bienvenue " . $_SESSION["user"]["nom"] . " !";
                $_SESSION["typeMessage"] = "success";
                header("location:" . URLSITE);
                exit();
            }
        }
    }
}

require_once("./inc/header.php");
?>

<div class="container">
    <?php if(isset($_SESSION["message"])) : ?>
        <div class="row">
            <div class="col my-5">
                <div class="alert alert-<?php echo $_SESSION["typeMessage"] ?>"><?= $_SESSION["message"] ?></div>
            </div>
        </div>
    <?php
        unset($_SESSION["message"]);
        endif; ?>

    <div class="row row-cols-1 row-cols-lg-2 justify-content-center">
        <div class="col my-4">
            <h1>Connexion</h1>
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
                    <label for="mdp" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" id="mdp" name="mdp" class="form-control">
                        <span class="input-group-text" id="oeil"><i class="fa-solid fa-eye"></i></span>
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once("./inc/footer.php") ?>