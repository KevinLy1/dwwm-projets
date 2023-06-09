<?php 
function isConnected() {
    return isset($_SESSION["user"]); // permet l'existence $_SESSION["user] sur une connexion réussie
}

function goSQL($requete, $params = array()) {
    global $pdo;
    $stmt = $pdo->prepare($requete);

    // Sanitize : assainissement des valeurs
    if(!empty($params)) {
        foreach ($params as $index => $valeur) {
            $params[$index] = htmlspecialchars(trim($valeur));
            // $stmt->bindValue($index, $params[$index], PDO::PARAM_STR);
        }
    }

    $stmt->execute($params);
    // $stmt->execute();

    return $stmt;
}

function goSQLInt($requete, $params = array()) { // même fonction mais avec PARAM_INT
    global $pdo;
    $stmt = $pdo->prepare($requete);

    if(!empty($params)) {
        foreach ($params as $index => $valeur) {
            $params[$index] = htmlspecialchars(trim($valeur));
            $stmt->bindValue($index, $params[$index], PDO::PARAM_INT);
        }
    }
    $stmt->execute();

    return $stmt;
}

function getUserByName($nom) {
    $stmt = goSQL("SELECT * FROM users WHERE nom = :nom", array(
        "nom" => $nom
    ));

    if($stmt->rowCount() == 0) {
        return false; // utilisateur non trouvé
    }
    else {
        return $stmt->fetch();
    }
}

function getUserByEmail($email) {
    $stmt = goSQL("SELECT * FROM users WHERE email = :email", array(
        "email" => $email
    ));

    if($stmt->rowCount() == 0) {
        return false; // utilisateur non trouvé
    }
    else {
        return $stmt->fetch();
    }
}
?>