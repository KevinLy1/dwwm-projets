<?php 
require_once("./include/connect.php");
require_once("./include/init.php");
$pageTitle = "Todolist ECF2 - Archives";

if ($_GET) {
    if (array_key_exists("action", $_GET)) { // Vérification de l'existence de l'action dans $_GET avant d'entamer des manipulations de mise à jour, suppression, archivage, etc.
        if ($_GET['action'] == 'delete') {
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
    }

    if (array_key_exists("success", $_GET)) {
        switch($_GET['success']) {
            case "delete": // Message en cas de suppression
                $successUpdate = "<div class='alert alert-success'>La tâche $_GET[id_task] a été supprimée avec succès.</div>";
                break;
            default:
                $errorUpdate .= "<div class='alert alert-danger'>Requête invalide.</div>";
                break;
        }
    }
}

require_once("./include/header.php");
?>
    <?php echo (!empty($successUpdate) ? $successUpdate : (!empty($errorUpdate) ? $errorUpdate : "")) ?>
    <div class="row mt-5">
        <div class="col">
            <div class="card text-dark bg-subtle mb-3 h-100">
                <div class="card-header">
                    <h2>Tâches archivées</h2>                    
                </div>
                <div class="card-body">
                    <?php if (count(findTasks(4)) > 0) : ?>
                        <ul class="list-group">
                            <?php foreach (findTasks(4) as $task) : ?>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span><?php echo $task['description'] ?></span>
                                        <a href="?action=delete&id_task=<?php echo $task['id_task'] ?>" class="text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                    <div class="small text-muted"><?php echo date('d/m/Y à H:i:s', strtotime($task['date_task'])) ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="card-text">Aucune tâche archivée.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mb-5"><a href="./index.php" class="mt-5 btn btn-primary">Retour</a></div>

<?php require_once("./include/footer.php") ?>