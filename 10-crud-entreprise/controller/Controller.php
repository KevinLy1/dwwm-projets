<?php
namespace controller;

use Exception;

class Controller {
    private $dbEntityRepository;

    public function __construct() {
        $this->dbEntityRepository = new \model\EntityRepository;
    }

    public function handleRequest() {
        // On stocke la valeur de l'indice "op" transmis dans l'url
        $op = isset($_GET['op']) ? $_GET['op'] : 'list';
        try {
            if($op === 'add' || $op === 'update')
                $this->save($op); // si on ajoute ou on modifie un employé, la méthode save() sera exécutée
            elseif($op === 'select')
                $this->select();  // si on sélectionne un employé, la méthode select() sera exécutée
            elseif($op === 'delete')
                $this->delete();  // si on supprime un employé, la méthode delete() sera exécutée
            elseif($op === 'search')
                $this->search();  // si on recheche un employé, la méthode search() sera exécutée
            elseif($op === 'list')
                $this->selectAll(); // cas par défaut, la méthode list() sera exécutée
            else 
                throw new Exception("La page n'a pas été trouvé :(", 404); // en cas d'erreur, on lève une exception
        } catch (\Exception $e) {
            echo '<div style="width: 400px; padding: 15px; background: #CCE5FF; border-radius: 5px; margin: 0 auto; color: white; text-align: center;">';
            echo "🛑" . $e->getCode() . " " . $e->getMessage();
            echo '</div>';
        }
    }

    // Méthode permettant de construire une vue
    public function render($layout, $template, $parameters = []) {
        extract($parameters);
        ob_start();
        require_once "views/$template";
        $content = ob_get_clean();
        ob_start();
        require_once "views/$layout";
        
        return ob_end_flush();
    }

    // Méthode permettant d'afficher tous les employés
    public function selectAll($alert = "") {
        $this->render("layout.php", "affichage_employes.php", [
            "title" => "GESTION DES EMPLOYÉS",
            "data" => $this->dbEntityRepository->selectAllEntityRepo(),
            "fields" => $this->dbEntityRepository->getFields(),
            "id" => "id_" . $this->dbEntityRepository->table,
            "message" => "Ci-dessous, vous trouverez un tableau contenant l'ensemble des employés de l'entreprise",
            "alert" => $alert
        ]);
    }

    // Méthode permettant de sélectionner et d'afficher le détail d'un employé
    public function select() {
        if(!empty($_GET["id"])) {
            $id = $_GET["id"];

            if($this->dbEntityRepository->selectEntityRepo($id)) { // s'assurer que l'id existe dans la BDD
                $this->render("layout.php", "detail_employe.php", [
                    "title" => "DÉTAIL D'UN EMPLOYÉ",
                    "data" => $this->dbEntityRepository->selectEntityRepo($id),
                    "id" => "id_" . $this->dbEntityRepository->table,
                    "message" => "Ci-dessous, vous trouverez le détail de l'employé n°$id"
                ]);
            } else {
                $this->selectAll("🛑 L'employé sélectionné n'existe pas ou l'id n'a pas été trouvé.");
            }
        } else {
            $this->selectAll("🛑 L'employé sélectionné n'existe pas ou l'id n'a pas été trouvé.");
        }
    }

    // Méthode permettant d'enregister ou de modifier un employé
    public function save($op) {
        $id = $_GET["id"] ?? NULL;
        $values = ($op === "update") ? $this->dbEntityRepository->selectEntityRepo($id) : "";

        if($_POST) {
            // Récupération des données et envoi des données en base
            $this->dbEntityRepository->saveEntityRepo();
            // Renvoyer l'utilisateur sur la page d'accueil // Informer l'utilisateur que l'action est un succès
            $alert = ($op === "update") ? "✅ Les informations de l'employé n°$id ont été mis à jour avec succès !" : "✅ Nouvel employé créé avec succès";
            $this->selectAll($alert);
        }

        $this->render("layout.php", "contact_form.php", [
            "title" => "Formulaire d'ajout ou de modification d'employé",
            "message" => "Ci-dessous, vous trouverez le formulaire pour ajouter ou modifier un employé",
            "op" => $op,
            "values" => $values,
            "fields" => $this->dbEntityRepository->getFields()
        ]);
    }

    // Créer la méthode delete() permettant de supprimer un employé de la BDD
    // Une fois supprimé, renvoyer l'utilisateur sur le tableau des employés et informer l'utilisateur que l'action est un succès
    public function delete() {
        if(!empty($_GET["id"])) {
            $id = $_GET["id"];

            if($this->dbEntityRepository->selectEntityRepo($id)) { // s'assurer que l'id existe dans la BDD
                $this->dbEntityRepository->deleteEntityRepo($id);
                $this->selectAll("✅ Les informations de l'employé n°$id ont été supprimées définitivement.");
            } else {
                $this->selectAll("🛑 L'employé sélectionné n'existe pas ou l'id n'a pas été trouvé.");
            }
        } else {
            $this->selectAll("🛑 L'employé sélectionné n'existe pas ou l'id n'a pas été trouvé.");
        }
    }

    // Créer une fonctionnalité recherche qui permet de trouver un employé avec son nom et ou son prénom
    public function search() {
        if(!empty($_GET["name"])) {
            $name = $_GET["name"];

            if($this->dbEntityRepository->searchEntityRepo($name)) {
                $this->render("layout.php", "recherche.php", [
                    "title" => "Résultats de votre recherche",
                    "data" => $this->dbEntityRepository->searchEntityRepo($name),
                    "fields" => $this->dbEntityRepository->getFields(),
                    "id" => "id_" . $this->dbEntityRepository->table,
                    "message" => "Ci-dessous, vous trouverez les employés correspondant à vos critères de recherche : \"$name\"."
                ]);
            } else {
                $this->selectAll("🛑 Aucun employé correspondant au nom \"$name\" n'a été trouvé.");
            }
        } else {
            $this->selectAll("Veuillez entrer un critère de recherche.");
        }
    }
}