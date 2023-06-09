<?php 
require_once("./inc/init.php");
$pageTitle = "Accueil";

require_once("./inc/header.php");

$count = goSQL("SELECT COUNT(*) AS nbArticles FROM posts")->fetch();
$nbArticles = $count["nbArticles"];

// Système de pagination (uniquement si le nombre d'articles > 0, sinon redirection infinie)
if($nbArticles > 0) {
    $currentPage = isset($_GET['page']) && $_GET['page'] !== "" ? $_GET['page'] : 1;

    if(!is_numeric($currentPage) || $currentPage <= 0) { // Redirection vers la première page si le numéro de page est invalide
        $_SESSION["message"] = "Numéro de page invalide. Retour à la page 1.";
        $_SESSION["typeMessage"] = "warning";

        header("location: ?page=1");
        exit();
    }

    $articlesPerPage = 8; // Nombre d'articles par page
    $nbPages = ceil($nbArticles / $articlesPerPage); // Nombre de pages
    $firstArticle = ($currentPage - 1) * $articlesPerPage; // Déterminer le premier article de la page

    // Vérification si le numéro de page existe
    if ($currentPage > $nbPages) { // Redirection vers la première page si le numéro de page n'existe pas
        $_SESSION["message"] = "La page demandée ($currentPage) n'existe pas. Retour à la première page disponible.";
        $_SESSION["typeMessage"] = "warning";

        header("location: ?page=1");
        exit();
    }

    // Requête de sélection des articles avec pagination
    $articles = goSQLInt("SELECT p.*, u.nom, COUNT(c.id_comment) AS nbComments FROM posts p LEFT JOIN users u USING (id_user) LEFT JOIN comments c USING (id_post) GROUP BY p.id_post ORDER BY id_post DESC LIMIT :firstArticle, :articlesPerPage", array("firstArticle" => $firstArticle, "articlesPerPage" => $articlesPerPage))->fetchAll();
}
?>

<div class="container">
    <?php if(isset($_SESSION["message"])) : ?>
        <div class="row">
            <div class="col my-5">
                <div class="alert alert-<?php echo $_SESSION["typeMessage"] ?>"><?= $_SESSION["message"] ?></div>
            </div>
        </div>
        <?php unset($_SESSION["message"]); ?>
    <?php endif; ?>
    <h1>Bienvenue sur le blog communautaire, votre source inépuisable d'inspiration et de partage !</h1>
    <div class="row mt-3 mb-4">
        <div class="col-md-6">
            <p class="mb-0">Plongez au cœur d'un océan d'articles captivants rédigés par des esprits passionnés du monde entier.</p>
            <p class="mb-0">De la littérature à l'art, de la science à la mode, notre communauté vous offre une expérience unique où les idées s'épanouissent.</p>
            <p class="mb-0">Explorez sans limite, élargissez vos horizons et laissez-vous emporter par la richesse de notre contenu.</p>
        </div>
        <div class="col-md-6">
            <p class="mb-0">Préparez-vous à être émerveillé, à être informé et à vous épanouir dans cette aventure passionnante.</p>
            <p class="mb-0">Bienvenue dans notre monde, où chaque article est une invitation à découvrir de nouvelles perspectives.</p>
        </div>
    </div>
    <h2>Consultation des articles</h2>
    <hr>
    <div class="row">
        <?php if(!empty($articles)) : ?>

        <?php if($nbPages > 1) : ?>
        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                    <a href="?page=1" class="page-link"><i class="fas fa-angle-double-left"></i></a>
                </li>
                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                    <a href="?page=<?= $currentPage - 1 ?>" class="page-link"><i class="fas fa-angle-left"></i></a>
                </li>
                <?php for($page = 1; $page <= $nbPages; $page++): ?>
                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                        <a href="?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($currentPage == $nbPages) ? "disabled" : "" ?>">
                    <a href="?page=<?= $currentPage + 1 ?>" class="page-link"><i class="fas fa-angle-right"></i></a>
                </li>
                <li class="page-item <?= ($currentPage == $nbPages) ? "disabled" : "" ?>">
                    <a href="?page=<?= $nbPages ?>" class="page-link"><i class="fas fa-angle-double-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

        <?php foreach($articles as $article) : ?>
            <div class="col-md-6 col-lg-4 col-xl-3 my-3 mb-sm-0">
                <div class="card h-100 w-100">
                    <img src="<?php echo $article["photo"] ? URLSITE . "photos/" . $article["photo"] : URLSITE . "photos/default.svg" ?>" class="card-img-top object-fit-cover" alt="<?php echo $article["titre"] ? $article["titre"] : "" ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">#<?php echo $article["id_post"] ?> - <?php echo $article["titre"] ?></h5>
                        <div class="small text-muted mb-3">Par <?php echo ($article["nom"] ?? "Utilisateur supprimé") . ", le " . date("d/m/Y à H:i", strtotime($article["date_post"])) ?></div>
                        <p class="card-text"><?php echo iconv_strlen($article["contenu"]) > 100 ? nl2br(substr($article["contenu"], 0, 100)) . "..." : nl2br($article["contenu"]) ?></p>
                        <div class="mt-auto d-flex justify-content-between">
                            <a href="<?php echo URLSITE . "article.php?id_post=$article[id_post]" ?>" class="btn btn-primary">
                                <i class="fas fa-newspaper"></i> Lire
                            </a>
                            <div class="comment-info">
                                <a href="<?php echo URLSITE . "article.php?id_post=$article[id_post]#comments" ?>" class="btn btn-success comment-button">
                                    <i class="fas fa-comments"></i>
                                    <span class="comment-count"><?php echo $article["nbComments"] ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if($nbPages > 1) : ?>
        <!-- Pagination -->
        <nav class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                    <a href="?page=1" class="page-link"><i class="fas fa-angle-double-left"></i></a>
                </li>
                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                    <a href="?page=<?= $currentPage - 1 ?>" class="page-link"><i class="fas fa-angle-left"></i></a>
                </li>
                <?php for($page = 1; $page <= $nbPages; $page++): ?>
                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                        <a href="?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($currentPage == $nbPages) ? "disabled" : "" ?>">
                    <a href="?page=<?= $currentPage + 1 ?>" class="page-link"><i class="fas fa-angle-right"></i></a>
                </li>
                <li class="page-item <?= ($currentPage == $nbPages) ? "disabled" : "" ?>">
                    <a href="?page=<?= $nbPages ?>" class="page-link"><i class="fas fa-angle-double-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    <?php else : ?>
        <div class="col">
            <div class="alert alert-info">Aucun article disponible pour le moment.</div>
        </div>
    <?php endif; ?>
    </div>
</div>


<?php require_once("./inc/footer.php") ?>