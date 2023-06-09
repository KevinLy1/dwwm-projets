<?php 
require_once("./inc/init.php");
$pageTitle = "Ajout d'article";

// Redirection pour les utilisateurs non connectés
if(!isConnected()) {
    header("location:" . URLSITE . "connexion.php");
    exit();
}

// Traitements
if(!empty($_GET)) {
    if(isset($_GET["action"])) {
        if($_GET["action"] == "edit") {
            if(isset($_GET["id_post"]) && is_numeric($_GET["id_post"])) {
                $articleData = goSQL("SELECT titre, contenu, photo FROM posts WHERE id_post = :id_post AND id_user = :id_user", array("id_post" => $_GET["id_post"], "id_user" => $_SESSION["user"]["id_user"]))->fetch(); // il faut vérifier que l'utilisateur ne puisse pas éditer les articles d'autres membres via l'URL
                
                if(empty($articleData)) {
                    $_SESSION["message"] = "L'article #$_GET[id_post] que vous avez tenté de modifier n'existe pas ou vous n'êtes pas l'auteur de l'article.";
                    $_SESSION["typeMessage"] = "danger";
        
                    header("location:" . URLSITE . "gestion.php");
                    exit();
                }
            }
            else {
                $_SESSION["message"] = "Action invalide.";
                $_SESSION["typeMessage"] = "danger";
        
                header("location:" . URLSITE . "gestion.php");
                exit();
            }
        }
        else {
            $_SESSION["message"] = "Action invalide.";
            $_SESSION["typeMessage"] = "danger";
    
            header("location:" . URLSITE . "gestion.php");
            exit();
        }
    }
}

// Si le formulaire est posté
if (!empty($_POST)) {
    // echo "<pre>"; var_dump($_POST); echo "</pre>";
    // echo "<pre>"; var_dump($_FILES); echo "</pre>";
    $erreurs = array();

    // Contrôle du titre
    if (empty(trim($_POST["titre"]))) {
        $erreurs[] = "Le titre est obligatoire.";
    }

    // Contrôle du contenu
    if (empty(trim($_POST["contenu"]))) {
        $erreurs[] = "L'article ne peut pas être vide.";
    }

    // Contrôle de la photo
    $typesMimeAutorises = array("image/jpeg", "image/png", "image/webp");
    if (!empty($_FILES["photo"]["name"])) {
        if (!in_array($_FILES['photo']['type'], $typesMimeAutorises)) {
            $erreurs[] = "Format de photo incorrect. Merci de choisir une image JPEG, PNG ou WEBP.";
        }
    }

    if (empty($erreurs)) {
        // INSERTION
        if (empty($_GET)) {
            // Gérer l'éventuelle photo
            if (!empty($_FILES["photo"]["name"])) {
                $nomFichier = uniqid() . "_" . $_FILES["photo"]["name"];
                $emplacement = $_SERVER["DOCUMENT_ROOT"] . URLSITE . "photos/";
                // $emplacement = __DIR__ . "/photos/";
                move_uploaded_file($_FILES['photo']["tmp_name"], $emplacement . $nomFichier);
                // copy($_FILES['photo']["tmp_name"], $emplacement . $nomFichier);
            }
            // Insertion en BDD
            // Compléter l'array $_POST
            $_POST["id_user"] = $_SESSION["user"]["id_user"];
            $_POST["photo"] = $nomFichier;
            // Lancement de la requête d'insertion
            goSQL("INSERT INTO posts (id_user, date_post, titre, contenu, photo) VALUES (:id_user, NOW(), :titre, :contenu, :photo)", $_POST);

            // Message de succès
            $_SESSION["message"] = "L'article a bien été ajouté.";
            $_SESSION["typeMessage"] = "success";

            // Redirection
            header("location:" . URLSITE . "gestion.php");
            exit();
        }

        // MISE À JOUR
        elseif (!empty($_GET)) {
            if (isset($_GET["action"])) {
                if ($_GET["action"] == "edit") {
                    if (isset($_GET["id_post"])) {
                        if (is_numeric($_GET["id_post"])) {
                            // Vérifier si les valeurs soumises sont différentes des valeurs actuelles
                            if ($_POST['titre'] !== html_entity_decode($articleData['titre']) || $_POST['contenu'] !== html_entity_decode($articleData['contenu']) || !empty($_FILES['photo']['name'])) { // il faut gérer les éventuels caractères accentuées ou spéciaux. Ex : l'apostrophe = &#039; en base de données donc la comparaison !== ne fonctionnerait pas
                                // Au moins un champ (titre, contenu ou photo) a été modifié, exécuter la requête de mise à jour
                                if (!empty($_FILES['photo']['name'])) { // Un nouveau fichier a été téléchargé, inclure le champ "photo" dans la requête de mise à jour
                                    $nomFichier = uniqid() . "_" . $_FILES["photo"]["name"];

                                    $emplacement = $_SERVER["DOCUMENT_ROOT"] . URLSITE . "photos/";
                                    move_uploaded_file($_FILES['photo']["tmp_name"], $emplacement . $nomFichier);

                                    // Récupérer l'ancien nom de fichier depuis la base de données
                                    $ancienNomFichier = goSQL("SELECT photo FROM posts WHERE id_post = :id_post", array("id_post" => $_GET["id_post"]))->fetchColumn();

                                    // Supprimer l'ancienne photo si elle existe
                                    if (!empty($ancienNomFichier)) {
                                        $ancienEmplacement = $_SERVER["DOCUMENT_ROOT"] . URLSITE . "photos/" . $ancienNomFichier;
                                        if (file_exists($ancienEmplacement)) {
                                            unlink($ancienEmplacement);
                                        }
                                    }

                                    // Compléter l'array $_POST
                                    $_POST["date_post"] = date('Y-m-d H:i:s');
                                    $_POST["photo"] = $nomFichier;
                                    $_POST["id_post"] = $_GET["id_post"];

                                    goSQL("UPDATE posts SET date_post = :date_post, titre = :titre, contenu = :contenu, photo = :photo WHERE id_post = :id_post", $_POST);

                                    $_SESSION["message"] = "L'article $_GET[id_post] a bien été mis à jour.";
                                    $_SESSION["typeMessage"] = "success";

                                    // Redirection
                                    header("location:" . URLSITE . "gestion.php");
                                    exit();
                                }
                                else { // La photo n'a pas été modifiée, exclure le champ "photo" de la requête de mise à jour
                                    // Compléter l'array $_POST
                                    $_POST["date_post"] = date('Y-m-d H:i:s');
                                    $_POST["id_post"] = $_GET["id_post"];

                                    goSQL("UPDATE posts SET date_post = :date_post, titre = :titre, contenu = :contenu WHERE id_post = :id_post", $_POST);

                                    $_SESSION["message"] = "L'article $_GET[id_post] a bien été mis à jour.";
                                    $_SESSION["typeMessage"] = "success";

                                    // Redirection
                                    header("location:" . URLSITE . "gestion.php");
                                    exit();
                                }
                            }
                            else { // Aucun champ n'a été modifié
                                $_SESSION["message"] = "Aucun changement détecté. Aucune mise à jour n'a été effectuée.";
                                $_SESSION["typeMessage"] = "warning";

                                // Redirection
                                header("location:" . $_SERVER["REQUEST_URI"]);
                                exit();
                            }
                        }
                    }
                }
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
            <h1><?php echo empty($_GET) ? "Ajouter un article" : (isset($_GET["action"]) == "edit" ? "Éditer un article (#$_GET[id_post])" : "") ?></h1>
            <hr>
            <?php if(!empty($erreurs)) : ?>
                <div class="alert alert-danger"><?php echo implode("<br>", $erreurs) ?></div>
            <?php endif; ?>
            <!-- Pour une pièce jointe à récupérer avec $_FILES, il faut l'attribut enctype avec la valeur multipart/form-data -->
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre de l'article <span class="text-danger">*</span></label>
                    <input type="text" id="titre" name="titre" class="form-control" value="<?php echo $_POST["titre"] ?? $articleData["titre"] ?? "" ?>">
                </div>

                <div class="mb-3">
                    <label for="contenu" class="form-label">Contenu de l'article <span class="text-danger">*</span></label>
                    <textarea id="contenu" name="contenu" rows="5" class="form-control"><?php echo $_POST["contenu"] ?? $articleData["contenu"] ?? "" ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                </div>

                <?php if(!empty($articleData["photo"])) : ?>
                    <p>Photo actuelle (conservée si aucun changement) :</p>
                    <img src="<?php echo URLSITE . "photos/" . $articleData["photo"] ?>" alt="<?php echo $articleData["titre"] ?>" class="h-100 w-100 mb-5">
                <?php endif; ?>

                <div class="mb-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once("./inc/footer.php") ?>