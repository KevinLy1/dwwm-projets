<?php
require_once("./include/connect.php");
require_once("./include/init.php");
$pageTitle = "Todolist ECF2 - Accueil";

// Création d'une tâche et insertion dans la BDD via le formulaire
if($_POST) {
    $error = "";
    // Contraintes du formulaire
    if(!array_key_exists("description", $_POST) || iconv_strlen(trim($_POST["description"])) < 5 || iconv_strlen(trim($_POST["description"])) > 50) {
        $error = true;
    }

    // Vérification des erreurs avant de lancer la requête d'insertion
    if(empty($error)) { // si aucune erreur trouvée
        insertTask(); // requête d'insertion
        header("location: index.php?add=1"); // redirection vers avec un success=1 qui affichera un message de succès
        exit();
    }
    else { // si au moins une erreur est trouvée
        header("location: index.php?add=0"); // redirection vers avec un success=0 qui affichera un message de succès
        exit();
    }
}

// Traitement $_GET
if ($_GET) {
    if (array_key_exists("action", $_GET)) { // Vérification de l'existence de l'action dans $_GET avant d'entamer des manipulations de mise à jour, suppression, archivage, etc.
        // Cas 1 : Mise à jour du statut de la tâche
        if ($_GET['action'] == 'update_status') { 
            if (array_key_exists("id_task", $_GET) && is_numeric($_GET['id_task'])) { // Vérifier si l'ID de la tâche est spécifié et qu'il est bien au format numérique
                if (isTaskSet($_GET['id_task'])) { // si la tâche existe
                    updateTask($_GET['id_task'], $_GET['status']); // le statut se met à jour
                    header("location: ?success=update&id_task=$_GET[id_task]"); // Rediriger pour afficher le message de succès
                    exit();
                }
                else {
                    $errorUpdate .= "<div class='alert alert-danger'>La tâche $_GET[id_task] n'existe pas et ne peut donc pas être mise à jour.</div>";
                }
            }
            else {
                $errorUpdate .= "<div class='alert alert-danger'>Requête invalide.</div>";
            }
        }
        // Cas 2 : Suppression de la tâche
        elseif ($_GET['action'] == 'delete') {
            if (array_key_exists("id_task", $_GET) && is_numeric($_GET['id_task'])) { // Vérifier si l'ID de la tâche est spécifié et qu'il est bien au format numérique
                if (isTaskSet($_GET['id_task'])) { // si la tâche existe
                    deleteTask($_GET['id_task']); // la tâche est supprimée

                    header("location: ?success=delete&id_task=$_GET[id_task]"); // Rediriger pour afficher le message de succès
                    exit();
                }
                else {
                    $errorUpdate .= "<div class='alert alert-danger'>La tâche $_GET[id_task] n'existe pas et ne peut donc pas être supprimée.</div>";
                }
            }
            else {
                $errorUpdate .= "<div class='alert alert-danger'>Requête invalide.</div>";
            }
        }
        
        // Cas 3 : Archivage de la tâche
        elseif ($_GET['action'] == 'archive') {
            if (array_key_exists("id_task", $_GET) && is_numeric($_GET['id_task'])) { // Vérifier si l'ID de la tâche est spécifié et qu'il est bien au format numérique
                if (isTaskSet($_GET['id_task'])) { // si la tâche existe
                    updateTask($_GET['id_task'], 4); // la tâche est archivée

                    header("location: ?success=archive&id_task=$_GET[id_task]"); // Rediriger pour afficher le message de succès
                    exit();
                }
                else {
                    $errorUpdate .= "<div class='alert alert-danger'>La tâche $_GET[id_task] n'existe pas et ne peut donc pas être archivée.</div>";
                }
                }
            else {
                $errorUpdate .= "<div class='alert alert-danger'>Requête invalide.</div>";
            }
        }
    }

    // Vérifier le succès afin d'afficher les messages de succès ou d'erreur
    if(array_key_exists("add", $_GET)) {
        switch ($_GET["add"]) {
            case 0: // Message en cas d'erreur à l'insertion
                $errorCreation .= "<div class='alert alert-danger'>La description doit comporter au moins 5 caractères et maximum 50 caractères.</div>";
                break;
            case 1: // Message en cas d'insertion avec succès
                $successCreation = "<div class='alert alert-success'>Tâche insérée avec succès.</div>";
                break;
            default:
                $errorCreation .= "<div class='alert alert-danger'>Requête invalide.</div>";
                break;
        }
    }

    if (array_key_exists("success", $_GET)) {
        switch($_GET['success']) {
            case "update": // Message en cas de mis à jour
                $successUpdate = "<div class='alert alert-success'>La tâche $_GET[id_task] a été mise à jour avec succès.</div>";
                break;
            case "delete": // Message en cas de suppression
                $successUpdate = "<div class='alert alert-success'>La tâche $_GET[id_task] a été supprimée avec succès.</div>";
                break;
            case "archive": // Message en cas d'archvage
                $successUpdate = "<div class='alert alert-success'>La tâche $_GET[id_task] a été archivée avec succès.</div>";
                break;
            default:
                $errorUpdate .= "<div class='alert alert-danger'>Requête invalide.</div>";
                break;
        }
    }
}

require_once("./include/header.php");
?>
    <h2 class="mt-5">Ajouter une tâche</h2>
    <?php echo (!empty($successCreation) ? $successCreation : (!empty($errorCreation) ? $errorCreation : "")) ?>
    <form method="POST">
        <div class="mb-3">
            <label for="description" class="form-label">Description de la tâche (5 caractères minimum et 50 caractères maximum)</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="text-center"><button type="submit" class="btn btn-primary">Ajouter</button></div>
    </form>

    <h2 class="my-5">Consultation des tâches</h2>
    <?php echo (!empty($successUpdate) ? $successUpdate : (!empty($errorUpdate) ? $errorUpdate : "")) ?>
    <div class="row">

        <div class="col">
            <div class="card text-white bg-danger mb-3 h-100">
                <div class="card-header">
                    <h2>À Faire</h2>
                </div>
                <div class="card-body">
                    <?php if (count(findTasks(1)) > 0) : // findTasks($statut) au $statut = 1 => statut "À faire" ?> 
                        <ul class="list-group">
                            <?php foreach (findTasks(1) as $task) : ?>
                                <li class="list-group-item">
                                    <div class="row d-flex justify-content-between">
                                        <span class="col-9"><?php echo $task['description'] ?></span>
                                        <div class="col-3 d-flex align-items-center justify-content-evenly">
                                            <a href="?action=update_status&id_task=<?php echo $task['id_task'] ?>&status=2" class="text-dark"><i class="fas fa-arrow-right"></i></a>
                                            <a href="?action=delete&id_task=<?php echo $task['id_task'] ?>" class="text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                                    <div class="small text-muted"><?php echo date('d/m/Y à H:i:s', strtotime($task['date_task'])) ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="card-text">Aucune tâche à faire.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-dark bg-warning mb-3 h-100">
                <div class="card-header">
                    <h2>En cours</h2>
                </div>
                <div class="card-body">
                    <?php if (count(findTasks(2)) > 0) : // findTasks($statut) au $statut = 2 => statut "En cours" ?> 
                        <ul class="list-group">
                            <?php foreach (findTasks(2) as $task) : ?>
                                <li class="list-group-item">
                                    <div class="row d-flex justify-content-between">
                                        <span class="col-8"><?php echo $task['description'] ?></span>
                                        <div class="col-4 d-flex align-items-center justify-content-evenly">
                                            <a href="index.php?action=update_status&id_task=<?php echo $task['id_task'] ?>&status=1" class="text-dark"><i class="fas fa-arrow-left"></i></a>
                                            <a href="?action=update_status&id_task=<?php echo $task['id_task'] ?>&status=3" class="text-dark"><i class="fas fa-arrow-right"></i></a>
                                            <a href="?action=delete&id_task=<?php echo $task['id_task'] ?>" class="text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                                    <div class="small text-muted"><?php echo date('d/m/Y à H:i:s', strtotime($task['date_task'])) ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="card-text">Aucune tâche en cours.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-white bg-success mb-3 h-100">
                <div class="card-header">
                    <h2>Terminées</h2>
                </div>
                <div class="card-body">
                    <?php if (count(findTasks(3)) > 0) : ?>
                        <ul class="list-group">
                            <?php foreach (findTasks(3) as $task) : // findTasks($statut) au $statut = 3 => statut "Terminée" ?>
                                <li class="list-group-item">
                                    <div class="row d-flex justify-content-between">                                    
                                        <span class="col-9"><?php echo $task['description'] ?></span>
                                        <div class="col-3 d-flex align-items-center justify-content-evenly">
                                            <a href="?action=update_status&id_task=<?php echo $task['id_task'] ?>&status=2" class="text-dark"><i class="fas fa-arrow-left"></i></a>
                                            <a href="?action=delete&id_task=<?php echo $task['id_task'] ?>" class="text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')"><i class="fas fa-trash-alt"></i></a>
                                            <a href="?action=archive&id_task=<?php echo $task['id_task'] ?>&status=4" class="text-subtle" onclick="return confirm('Êtes-vous sûr de vouloir archiver cette tâche ?')"><i class="fas fa-archive"></i></a>
                                        </div>
                                    </div>
                                    <div class="small text-muted"><?php echo date('d/m/Y à H:i:s', strtotime($task['date_task'])) ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="card-text">Aucune tâche terminée.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mb-5"><a href="./archives.php" class="mt-5 btn btn-primary">Consulter les tâches archivées</a></div>

<?php require_once("./include/footer.php") ?>