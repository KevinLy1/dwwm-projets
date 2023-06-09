<?php 
require_once("./inc/init.php");
$pageTitle = isset($_GET["id_post"]) ? "Article #$_GET[id_post]" : "Article";

// Gestion des commentaires
if(!empty($_GET)) {
    if(isset($_GET["id_post"])) {
        if(is_numeric($_GET["id_post"])) {
            $article = goSQL("SELECT * FROM posts LEFT JOIN users USING (id_user) WHERE id_post = :id_post", array("id_post" => $_GET["id_post"]))->fetch();
            $comments = goSQL("SELECT u.nom, c.id_comment, c.date_comment, c.contenu, c.id_user FROM comments c LEFT JOIN posts p ON c.id_post = p.id_post LEFT JOIN users u ON c.id_user = u.id_user WHERE c.id_post = :id_post ORDER BY c.date_comment DESC", array("id_post" => $_GET["id_post"]))->fetchAll();
            
            if(!empty($article)) {
                if(isset($_GET["action"])) {
                    if($_GET["action"] == "delete_comment") {
                        if(isset($_GET["id_comment"]) && is_numeric($_GET["id_comment"])) {
                            $commentToDelete = goSQL("SELECT * FROM comments WHERE id_comment = :id_comment AND id_user = :id_user AND id_post = :id_post", array("id_comment" => $_GET["id_comment"], "id_user" => $_SESSION["user"]["id_user"], "id_post" => $_GET["id_post"]))->fetch(); // il faut vérifier que l'utilisateur ne puisse supprimer que son propre commentaire, et que ce commentaire soit bien lié à l'article
        
                            if(!empty($commentToDelete)) {
                                goSQL("DELETE FROM comments WHERE id_comment = :id_comment AND id_post = :id_post", array("id_comment" => $_GET["id_comment"], "id_post" => $_GET["id_post"]));
                                
                                // Message de succès
                                $_SESSION["message"] = "Votre commentaire a bien été supprimé.";
                                $_SESSION["typeMessage"] = "success";

                                header("location:" . $_SERVER["PHP_SELF"] . "?id_post=" . $_GET["id_post"]);
                                exit();
                            }
                            else {
                                $_SESSION["message"] = "Le commentaire que vous avez tenté de supprimer n'existe pas ou vous n'êtes pas son auteur.";
                                $_SESSION["typeMessage"] = "danger";
                    
                                header("location:" . $_SERVER["PHP_SELF"] . "?id_post=" . $_GET["id_post"]);
                                exit();
                            }
                        }
                        else {
                            $_SESSION["message"] = "Action invalide.";
                            $_SESSION["typeMessage"] = "danger";
                        }
                    }
                }
            }
            else {
                $_SESSION["message"] = "L'article $_GET[id_post] n'existe pas ou plus.";
                $_SESSION["typeMessage"] = "danger";
            }
        }
        else {
            $_SESSION["message"] = "Action invalide.";
            $_SESSION["typeMessage"] = "danger";
        }
    }
    else {
        $_SESSION["message"] = "Action invalide.";
        $_SESSION["typeMessage"] = "danger";
    }
}
else {
    $_SESSION["message"] = "Erreur. Veuillez sélectionner un article.";
    $_SESSION["typeMessage"] = "danger";

    header("location:" . URLSITE);
    exit();
}

// Si le formulaire de commentaire est posté
if(!empty($_POST)) {
    $erreurs = array();

    // Contrôle du contenu
    if(empty(trim($_POST["contenu"])) ) {
        $erreurs[] = "Le commentaire ne peut pas être vide.";
    }

    if(empty($erreurs)) {
        // Insertion en BDD
        // Compléter l'array $_POST
        $_POST["id_user"] = $_SESSION["user"]["id_user"];
        $_POST["id_post"] = $_GET["id_post"];
        
        // Lancement de la requête
        goSQL("INSERT INTO comments (id_user, id_post, date_comment, contenu) VALUES (:id_user, :id_post, NOW(), :contenu)", $_POST);

        // Message de succès
        $_SESSION["message"] = "Le commentaire a bien été ajouté.";
        $_SESSION["typeMessage"] = "success";

        // Redirection
        header("location:" . $_SERVER["REQUEST_URI"]);
        exit();
    }
}

require_once("./inc/header.php");
?>

<div class="container">
    <a href="<?php echo URLSITE ?>" class="btn btn-primary mb-5">Retour à la liste des articles</a>

    <?php if(isset($_SESSION["message"])) : ?>
        <div class="row">
            <div class="col">
                <div class="alert alert-<?php echo $_SESSION["typeMessage"] ?>"><?= $_SESSION["message"] ?></div>
            </div>
        </div>
    <?php
        unset($_SESSION["message"]);
        endif; ?>

    <!-- Affichage de l'article -->
    <?php if(!empty($_GET) && !empty($article)) : ?>
        <div class="article-section article-header">
            <h1 class="article-title">Article #<?php echo $article["id_post"] ?></h1>
            <h2 class="article-subtitle"><?php echo $article["titre"] ?></h2>
            <div class="article-meta small text-muted">Rédigé par <?php echo ($article["nom"] ?? "Utilisateur supprimé") . ", le " . date('d/m/Y à H:i:s', strtotime($article["date_post"])) ?></div>
        </div>
        <div class="article-section article-content">
            <div class="row">
                <?php if(!empty($article["photo"])) : ?>
                <div class="col-md-6">
                    <div class="article-image">
                        <img src="<?php echo URLSITE . "photos/" . $article["photo"] ?>" alt="<?php echo $article["titre"] ?>" style="max-width: 100%;">
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-md-<?php echo (!empty($article["photo"])) ? '6' : '12'; ?>">
                    <div class="article-text"><?php echo nl2br($article["contenu"]) ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Affichage des commentaires -->
    <?php if(!empty($comments)) : ?>
    <h2 class="mt-5" id="comments"><?php echo count($comments) > 1 ? count($comments) . " commentaires" : "1 commentaire" ?></h2>
    <hr>
    <?php foreach($comments as $comment) : ?>
        <div class="row">
            <div class="col">
                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author"><?php echo ($comment["nom"] ?? "Utilisateur supprimé") ?></span> | <span class="comment-date"><?php echo date("d/m/Y à H:i", strtotime($comment["date_comment"])) ?></span>
                        <?php if (isConnected()) : ?>
                            <?php $isCurrentUserComment = ($comment["id_user"] === $_SESSION["user"]["id_user"]); ?>
                            <?php if ($isCurrentUserComment) : ?>
                                <a class="comment-delete" href="?<?php echo http_build_query(array_merge($_GET, array("action" => "delete_comment", "id_comment" => $comment["id_comment"]))) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre commentaire ?')"><i class="fas fa-trash-alt"></i></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="comment-content">
                        <p><?php echo nl2br($comment["contenu"]) ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php elseif(!empty($_GET) && !empty($article)) : ?>
    <div class="row">
        <div class="col">
            <div class="alert alert-info mt-5">Pas encore de commentaires.</div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Ajout du formulaire pour poster un commentaire si connecté -->
    <?php if(isConnected() && !empty($_GET) && !empty($article)) : ?>
    <div class="row row-cols-1 row-cols-lg-2">
        <div class="col my-4">
            <h3>Ajouter un commentaire</h3>
            <hr>
            <?php if(!empty($erreurs)) : ?>
                <div class="alert alert-danger"><?php echo implode("<br>", $erreurs) ?></div>
            <?php endif; ?>
            <!-- Pour une pièce jointe à récupérer avec $_FILES, il faut l'attribut enctype avec la valeur multipart/form-data -->
            <form method="post">
                <div class="mb-3">
                    <label for="contenu" class="form-label">Votre commentaire <span class="text-danger">*</span></label>
                    <textarea id="contenu" name="contenu" rows="5" class="form-control"><?php echo $_POST["contenu"] ?? "" ?></textarea>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Commenter l'article</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once("./inc/footer.php") ?>
