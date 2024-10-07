
<?php
require_once(__DIR__ . '/../models/Eleve.php'); // Inclure le modèle Élève

class EleveController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function index() {
        $eleves = $this->model->getAllEleves(); // Récupérer tous les élèves
        require '../app/views/eleve/list.php'; // Inclure la vue avec les données
    }
}
?>
