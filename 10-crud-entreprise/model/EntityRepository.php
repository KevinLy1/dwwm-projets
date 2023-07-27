<?php
namespace model;

class EntityRepository {
    private $db;
    public $table;

    // Méthode permettant de construire la connexion à la BDD
    public function getDb() {
        if(!$this->db) {
            try {
                $xml = simplexml_load_file("app/config.xml");

                $this->table = $xml->table;
                try {
                    $this->db = new \PDO("mysql:host=" . $xml->host . ";dbname=" . $xml->db, $xml->user, $xml->password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
                } catch (\PDOException $e) {
                    echo "<div style='width: 400px; padding: 15px; background: #CCE5FF; border-radius: 5px; margin: 0 auto; color: white; text-align: center;'>";
                    echo "🔴 Une erreur s'est produite : " . $e->getMessage();
                    echo "</div>";
                }
            } catch (\Exception $e) {
                echo "<div style='width: 400px; padding: 15px; background: #CCE5FF; border-radius: 5px; margin: 0 auto; color: white; text-align: center;'>";
                echo "🔴 Une erreur s'est produite : " . $e->getMessage();
                echo "</div>";
            }
        }

        return $this->db;
    }

    // Méthode permettant de sélectionner l'ensemble des employés de l'entreprise (dans la table "employés")
    public function selectAllEntityRepo() {
        $data = $this->getDb()->query("SELECT * FROM " . $this->table);
        $r = $data->fetchAll(\PDO::FETCH_ASSOC);

        return $r;
    }

    // Méthode permettant de sélectionner tous les noms des colonnes de la table "employes"
    public function getFields() {
        $data = $this->getDb()->query("DESC " . $this->table);
        $r = $data->fetchAll(\PDO::FETCH_ASSOC);

        return array_slice($r, 1);
    }

    // Méthode permettant de sélectionner un employé dans la BDD en fonction de son ID
    public function selectEntityRepo($id) {
        $data = $this->getDb()->query("SELECT * FROM " . $this->table . " WHERE id_" . $this->table . " = " . $id);
        $r = $data->fetch(\PDO::FETCH_ASSOC);

        return $r;
    }

    // Méthode permettant d'ajouter ou de modifier un employé
    public function saveEntityRepo() {
        $id = $_GET["id"] ?? "NULL";
        $this->getDb()->query('REPLACE INTO ' . $this->table . '(id_' . $this->table . ',' . implode(',', array_keys($_POST)) . ') VALUES (' . $id . ',' . "'" . implode("','", $_POST) . "'" . ')');
    }

    // Méthode permettant de supprimer un employé
    public function deleteEntityRepo($id) {
        $this->getDb()->query('DELETE FROM ' . $this->table . " WHERE id_" . $this->table . " = " . $id);
    }

    // Méthode permettant de recherche un employé sur son nom ou son prénom
    public function searchEntityRepo($value) {
        $value = htmlspecialchars(trim($value));

        $data = $this->getDb()->prepare("SELECT * FROM " . $this->table . " WHERE nom LIKE :nom OR prenom LIKE :prenom");
        $data->bindValue(":nom", "%".$value."%", \PDO::PARAM_STR);
        $data->bindValue(":prenom", "%".$value."%", \PDO::PARAM_STR);
        $data->execute();
        $r = $data->fetchAll(\PDO::FETCH_ASSOC);

        return $r;
    }
}
?>