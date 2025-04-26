<?php
require_once __DIR__ . '/../models/Service.php';  
require_once __DIR__ . '/../models/DemandeService.php';  

class ServiceController {

    // Affiche tous les services et les demandes dans le back-office
    public function index() {
        // Récupération des services
        $service = new Service();
        $connService = $service->getConnection();
        $stmtService = $connService->query("SELECT * FROM " . $service->getTable());
        $services = $stmtService->fetchAll(PDO::FETCH_ASSOC);
    
        // Récupération des demandes
        $demande = new DemandeService();
        $connDemande = $demande->getConnection();
        $stmtDemande = $connDemande->query("SELECT * FROM " . $demande->getTable());
        $demandes = $stmtDemande->fetchAll(PDO::FETCH_ASSOC);
    
        // Appel de la vue (back-office.php)
        include __DIR__ . '/../views/back_office.php';
    }
    
    // Affiche les services disponibles côté client (front-office)
    public function front() {
        $service = new Service();
        $conn = $service->getConnection();

        // Récupérer les services disponibles (disponible = 1)
        $stmt = $conn->query("SELECT * FROM " . $service->getTable() . " WHERE disponible = 1");
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Afficher la vue du front-office avec les services disponibles
        include __DIR__ . '/../views/front_office.php';
    }

    // Affiche le formulaire de création d'un service
    public function create() {
        include __DIR__ . '/../views/create_service.php';
    }

    // Crée un nouveau service sans image
    public function store($data) {
        $service = new Service();
        $conn = $service->getConnection();

        // Insertion du service dans la base de données sans l'image
        $stmt = $conn->prepare("INSERT INTO " . $service->getTable() . " (nom_service, description, categorie, prix_estime, disponible) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nom_service'],
            $data['description'],
            $data['categorie'],
            $data['prix_estime'],
            $data['disponible']
        ]);

        // Redirection après l'ajout
        header("Location: index.php?action=back_office");
        exit();
    }

    // Affiche le formulaire de modification d'un service
    public function edit($id) {
        $service = new Service();
        $conn = $service->getConnection();

        // Récupérer les données du service à modifier
        $stmt = $conn->prepare("SELECT * FROM " . $service->getTable() . " WHERE id_service = ?");
        $stmt->execute([$id]);
        $serviceToEdit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($serviceToEdit) {
            // Affiche le formulaire avec les données existantes du service
            include __DIR__ . '/../views/back_office.php';
        } else {
            echo "Service non trouvé.";
        }
    }

    // Met à jour un service sans modification de l'image
    public function update($id, $data) {
        $service = new Service();
        $conn = $service->getConnection();

        // Mettre à jour les informations du service sans l'image
        $stmt = $conn->prepare("UPDATE " . $service->getTable() . " SET nom_service=?, description=?, categorie=?, prix_estime=?, disponible=? WHERE id_service=?");
        $stmt->execute([
            $data['nom_service'],
            $data['description'],
            $data['categorie'],
            $data['prix_estime'],
            $data['disponible'],
            $id
        ]);

        // Redirection après la mise à jour
        header("Location: index.php?action=back_office");
        exit();
    }

    // Supprime un service
    public function delete($id) {
        $service = new Service();
        $conn = $service->getConnection();

        // Supprimer le service de la base de données
        $stmt = $conn->prepare("DELETE FROM " . $service->getTable() . " WHERE id_service = ?");
        $stmt->execute([$id]);

        // Redirection après la suppression
        header("Location: index.php?action=back_office");
        exit();
    }
}
?>
