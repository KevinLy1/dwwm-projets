<?php
// Création du PDO pour se connecter à la BDD
$pdo = new PDO('mysql:host=localhost;dbname=todolist', "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// Requête d'insertion
function insertTask() {
    global $pdo;
    $insertQuery = $pdo->prepare("INSERT INTO tasks (date_task, description, status) VALUES (:date_task, :description, :status)");
    $insertQuery->bindValue(":date_task", date("Y-m-d H:i:s"));
    $insertQuery->bindValue(":description", trim($_POST["description"]));
    $insertQuery->bindValue(":status", 1);

    return $insertQuery->execute();
}

// Requête SQL pour retrouver les tâches selon le statut
function findTasks($status) {
    global $pdo;
    $findQuery = "SELECT * FROM tasks WHERE status = $status";
    /*
    $status = 1 => Tâches "À faire"
    $status = 2 => Tâches "En cours"
    $status = 3 => Tâches "Terminées"
    $status = 4 => Tâches "Archivées"
    */
    return $pdo->query($findQuery)->fetchAll(PDO::FETCH_ASSOC);
}

// Vérification de l'existence de la tâche
function isTaskSet($id_task) {
    global $pdo;
    $taskQuery = "SELECT * FROM tasks WHERE id_task = $id_task";
    
    return $pdo->query($taskQuery)->fetch(PDO::FETCH_ASSOC);
}

// Mise à jour du statut de la tâche
function updateTask($id_task, $status) {
    global $pdo;
    $updateQuery = $pdo->prepare("UPDATE tasks SET status = :status WHERE id_task = :id_task");
    $updateQuery->bindValue(":id_task", $id_task);
    $updateQuery->bindValue(":status", $status);

    return $updateQuery->execute();
}

// Suppression de la tâche
function deleteTask($id_task) {
    global $pdo;
    $deleteQuery = $pdo->prepare("DELETE FROM tasks WHERE id_task = :id_task");
    $deleteQuery->bindValue(":id_task", $id_task);

    return $deleteQuery->execute();
}
?>