<?php 
require_once("./inc/init.php");
$pageTitle = "Profil";

// Redirection vers la page de connexion si l'utilisateur n'est pas connecté
if(!isConnected()) {
    header("location:" . URLSITE . "connexion.php");
    exit();
}

// Récupération des informations de l'utilisateur pour sa page profil
$userData = goSQL("SELECT * FROM users WHERE id_user = :id_user", array("id_user" => $_SESSION["user"]["id_user"]))->fetch();

// Statistiques utilisateur
$userStatisticsArticles = goSQL("SELECT COUNT(id_post) AS nbArticles FROM posts WHERE id_user = :id_user", array("id_user" => $_SESSION["user"]["id_user"]))->fetch();
$userStatisticsComments = goSQL("SELECT COUNT(id_comment) AS nbComments FROM comments WHERE id_user = :id_user", array("id_user" => $_SESSION["user"]["id_user"]))->fetch();

if(!empty($_GET)) {
    if(isset($_GET["action"])) {
        if($_GET["action"] == "delete") {
            if(isset($_GET["id_user"]) && is_numeric($_GET["id_user"])) {
                if($_GET["id_user"] == $_SESSION["user"]["id_user"]) { // il faut vérifier que l'utilisateur ne puisse pas supprimer un autre utilisateur
                    $deleteUser = goSQL("DELETE FROM users WHERE id_user = :id_user", array("id_user" => $_GET["id_user"]));
                    unset($_SESSION["user"]);
    
                    // Message de succès
                    $_SESSION["message"] = "Votre compte a bien été supprimé";
                    $_SESSION["typeMessage"] = "success";
    
                    header("location:" . URLSITE);
                    exit();
                }
                else {
                    $_SESSION["message"] = "N'essayez pas de supprimer le compte d'un autre utilisateur.";
                    $_SESSION["typeMessage"] = "danger";
        
                    header("location:" . $_SERVER["PHP_SELF"]);
                    exit();
                }
            }
            else {
                $_SESSION["message"] = "Action invalide.";
                $_SESSION["typeMessage"] = "danger";
        
                header("location:" . $_SERVER["PHP_SELF"]);
                exit();
            }
        }
        elseif($_GET["action"] == "edit") {
            if(isset($_GET["id_user"]) && is_numeric($_GET["id_user"])) {
                if($_GET["id_user"] == $_SESSION["user"]["id_user"]) { // il faut vérifier que l'utilisateur ne puisse pas modifier les informations d'un autre utilisateur
                    // Si le formulaire est posté
                    if (!empty($_POST)) {
                        $erreurs = array();
                        $pseudoModifie = false;
                        $emailModifie = false;
                        $mdpModifie = false;

                        // Contrôle du nom d'utilisateur
                        if (empty(trim($_POST["nom"]))) {
                            $erreurs[] = "Merci de saisir votre nom d'utilisateur.";
                        }
                        else {
                            // Vérifier si le nom d'utilisateur a été modifié
                            if ($_POST["nom"] !== $userData["nom"]) {
                                $pseudoModifie = true;
                                if (getUserByName($_POST["nom"])) {
                                    $erreurs[] = "Ce nom est déjà utilisé. Merci d'en choisir un autre.";
                                }
                            }
                        }

                        // Contrôle de l'e-mail
                        if (empty(trim($_POST["email"]))) {
                            $erreurs[] = "Merci de saisir votre adresse e-mail.";
                        }
                        else {
                            // Vérifier si l'e-mail a été modifié
                            if ($_POST["email"] !== $userData["email"]) {
                                $emailModifie = true;
                                if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                                    $erreurs[] = "Format d'adresse e-mail incorrect.";
                                }
                                elseif (getUserByEmail($_POST["email"])) {
                                    $erreurs[] = "Il existe déjà un compte avec cette adresse e-mail.";
                                }
                            }
                        }

                        // Contrôle de l'ancien mot de passe
                        if (empty(trim($_POST["actuelMdp"]))) {
                            $erreurs[] = "Merci de saisir votre mot de passe actuel.";
                        }
                        else {
                            // Vérifier si l'ancien mot de passe est correct
                            if (!password_verify($_POST["actuelMdp"], $userData["mdp"])) {
                                $erreurs[] = "Le mot de passe actuel est incorrect.";
                            }
                        }

                        // Contrôle du nouveau mot de passe
                        $pattern = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9_]{8,20}$#";
                        if (!empty(trim($_POST["nouveauMdp"]))) {
                            // Vérifier si le nouveau mot de passe a été modifié
                            if (!preg_match($pattern, $_POST["nouveauMdp"])) {
                                $erreurs[] = "Le nouveau mot de passe doit comporter entre 8 et 20 caractères et contenir au moins une minuscule, une majuscule et un chiffre.";
                            }
                            $mdpModifie = true;
                        }

                        if (empty($erreurs)) {
                            // Tout est OK
                            if ($pseudoModifie || $emailModifie || $mdpModifie) {
                                // Si le pseudo, l'e-mail ou le mot de passe a été modifié, mettre à jour la base de données
                                $params = array(
                                    "nom" => $_POST["nom"],
                                    "email" => $_POST["email"],
                                    "id_user" => $_GET["id_user"]
                                );
                                if ($mdpModifie) {
                                    $params["mdp"] = password_hash($_POST["nouveauMdp"], PASSWORD_DEFAULT);
                                }
                                goSQL("UPDATE users SET nom = :nom, email = :email" . ($mdpModifie ? ", mdp = :mdp" : "") . " WHERE id_user = :id_user", $params);
                                
                                $_SESSION["message"] = "Mise à jour du profil réussie.";
                                $_SESSION["typeMessage"] = "success";

                                $user = getUserByName($_POST["nom"]);
                                $_SESSION["user"] = $user;

                            }
                            else {
                                $_SESSION["message"] = "Aucun changement détecté.";
                                $_SESSION["typeMessage"] = "warning";

                                header("location:" . $_SERVER["REQUEST_URI"]);
                                exit();
                            }
                        
                            header("location:" . $_SERVER["PHP_SELF"]);
                            exit();
                        }                        
                    }
                }
                else {
                    $_SESSION["message"] = "N'essayez pas de modifier les informations d'un autre utilisateur.";
                    $_SESSION["typeMessage"] = "danger";
        
                    header("location:" . $_SERVER["PHP_SELF"]);
                    exit();
                }
            }
            else {
                $_SESSION["message"] = "Action invalide.";
                $_SESSION["typeMessage"] = "danger";
        
                header("location:" . $_SERVER["PHP_SELF"]);
                exit();
            }
        }
        else {
            $_SESSION["message"] = "Action invalide.";
            $_SESSION["typeMessage"] = "danger";
    
            header("location:" . $_SERVER["PHP_SELF"]);
            exit();
        }
    }
}

require_once("./inc/header.php");

// Page profil d'utilisateur
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
    
    <h1>Votre profil</h1>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h2>Statistiques</h2>
            <div class="row">
                <div class="col">
                    <ul class="list-group">
                        <li class="list-group-item">Nombre d'articles postés : <?php echo $userStatisticsArticles["nbArticles"] ?></li>
                        <li class="list-group-item">Nombre de commentaires postés : <?php echo $userStatisticsComments["nbComments"] ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h2>Informations du profil</h2>
            <?php if(empty($_GET)) : ?>
            <p>Nom d'utilisateur : <?php echo $userData["nom"] ?></p>
            <p>Adresse e-mail : <?php echo $userData["email"] ?></p>
            <a href="<?php echo URLSITE . "profil.php?action=edit&id_user=" . $_SESSION["user"]["id_user"] ?>" class="btn btn-warning">Éditer le profil</a>
            <a href="<?php echo URLSITE . "profil.php?action=delete&id_user=" . $_SESSION["user"]["id_user"] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?')">Supprimer le profil</a>
            <?php elseif(!empty($_GET) && $_GET["action"] == "edit") : ?>
                <?php if(!empty($erreurs)) : ?>
                <div class="alert alert-danger"><?php echo implode("<br>", $erreurs) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom d'utilisateur</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="<?php echo $_POST["nom"] ?? $userData["nom"] ?? "" ?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo $_POST["email"] ?? $userData["email"] ?? "" ?>">
                    </div>

                    <div class="mb-3">
                        <label for="actuelMdp" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" id="actuelMdp" name="actuelMdp" class="form-control">
                            <span class="input-group-text" id="oeilActuelMdp"><i class="fa-solid fa-eye"></i></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nouveauMdp" class="form-label">Nouveau mot de passe (si changement souhaité)</label>
                        <div class="input-group">
                            <input type="password" id="nouveauMdp" name="nouveauMdp" class="form-control">
                            <span class="input-group-text" id="oeilNouveauMdp"><i class="fa-solid fa-eye"></i></span>
                        </div>
                    </div>

                    <div class="mb-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once("./inc/footer.php") ?>