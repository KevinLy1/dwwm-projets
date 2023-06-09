<?php 
require_once("./inc/init.php");
$pageTitle = "Mes articles";

// Redirection pour les utilisateurs non connectés
if(!isConnected()) {
    header("location:" . URLSITE . "connexion.php");
    exit();
}

// Requête de sélection des articles de l'utilisateur
$userArticles = goSQL("SELECT p.*, COUNT(c.id_comment) AS nbComments FROM posts AS p LEFT JOIN comments AS c ON p.id_post = c.id_post WHERE p.id_user = :id_user GROUP BY p.id_post ORDER BY p.id_post DESC", array("id_user" => $_SESSION["user"]["id_user"]))->fetchAll();
// echo "<pre>"; print_r($userArticles); echo "</pre>";

if(!empty($_GET)) {
    if(isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id_post"]) && is_numeric($_GET["id_post"])) {
        $articleToDelete = goSQL("SELECT * FROM posts WHERE id_post = :id_post AND id_user = :id_user", array("id_post" => $_GET["id_post"], "id_user" => $_SESSION["user"]["id_user"]))->fetch(); // il faut vérifier que l'utilisateur ne puisse pas supprimer les articles d'autres membres via l'URL
        
        if(!empty($articleToDelete)) {
            if(!empty($articleToDelete["photo"])) {
                $file = $_SERVER["DOCUMENT_ROOT"] . URLSITE . "photos/" . $articleToDelete["photo"];

                if(file_exists($file)) {
                    unlink($file);
                }
            }
            goSQL("DELETE FROM posts WHERE id_post = :id_post", array("id_post" => $_GET["id_post"])); // il faut vérifier que l'utilisateur ne puisse pas supprimer les articles d'autres membres via l'URL

            // Message de succès
            $_SESSION["message"] = "L'article #$_GET[id_post] a bien été supprimé.";
            $_SESSION["typeMessage"] = "success";

            header("location:" . $_SERVER["PHP_SELF"]);
            exit();
        }
        else {
            $_SESSION["message"] = "L'article #$_GET[id_post] que vous avez tenté de supprimer n'existe pas ou vous n'êtes pas l'auteur de l'article.";
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
    <h1>Mes articles</h1>
    <hr>
    <div class="row">
        <?php if(!empty($userArticles)) : ?>
            <?php foreach($userArticles as $article) : ?>
                <div class="col-md-6 col-lg-4 col-xl-3 my-3 mb-sm-0">
                    <div class="card h-100 w-100">
                            <img src="<?php echo $article["photo"] ? URLSITE . "photos/" . $article["photo"] : URLSITE . "photos/default.svg" ?>" class="card-img-top object-fit-cover" alt="<?php echo $article["titre"] ? $article["titre"] : "" ?>">
                            <div class="card-body d-flex flex-column">
                            <h5 class="card-title">#<?php echo $article["id_post"] ?> - <?php echo $article["titre"] ?></h5>
                            <div class="small text-muted mb-3"><?php echo date("d/m/Y à H:i", strtotime($article["date_post"])) ?></div>
                            <p class="card-text"><?php echo iconv_strlen($article["contenu"]) > 100 ? nl2br(substr($article["contenu"], 0, 100)) . "..." : nl2br($article["contenu"]) ?></p>
                            <div class="mt-auto d-flex justify-content-between">
                                <div class="d-flex gap-2">
                                    <a href="<?php echo URLSITE . "article.php?id_post=$article[id_post]" ?>" class="btn btn-primary"><i class="fas fa-newspaper"></i> Lire</a>
                                    <div class="comment-info">
                                        <a href="<?php echo URLSITE . "article.php?id_post=$article[id_post]#comments" ?>" class="btn btn-success comment-button">
                                            <i class="fas fa-comments"></i>
                                            <span class="comment-count"><?php echo $article["nbComments"] ?></span>
                                        </a>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="<?php echo URLSITE . "form.php?action=edit&id_post=$article[id_post]" ?>" class="btn btn-warning"><i class="fa-sharp fa-solid fa-pen-to-square"></i></a>
                                    <a href="<?php echo URLSITE . "gestion.php?action=delete&id_post=" . $article["id_post"] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')"><i class="fa-sharp fa-solid fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Vous n'avez pas encore écrit d'article. <a href="./form.php">Commencez à écrire votre premier article.</a></p>
        <?php endif; ?>
    </div>
</div>

<?php require_once("./inc/footer.php") ?>