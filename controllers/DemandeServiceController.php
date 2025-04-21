<?php
require_once __DIR__ . '/../models/DemandeService.php'; // Inclut le modèle DemandeService

class DemandeServiceController {

    public function index() {
        $demande = new DemandeService();
        $demandes = $demande->getAll(); 
        include __DIR__ . '/../views/back_office.php';}

    public function front() {
        $demande = new DemandeService();
        $demandes = $demande->getAll(); 
        include __DIR__ . '/../views/front_office.php'; 
    }

    
    /*public function create() {
        include __DIR__ . '/../views/front_office.php'; 
    }*/

    public function store($data) {
        $demande = new DemandeService();
        $conn = $demande->getConnection();
    
        $stmt = $conn->prepare("
            INSERT INTO " . $demande->getTable() . " (service_id, client_id, description, date_demande, etat)
            VALUES (?, ?, ?, NOW(), 'en attente')
        ");
    
        $stmt->execute([
            $data['service_id'],
            $data['client_id'],
            $data['description']
        ]);
    
        header("Location: index.php?action=front_office");
        exit();
    }
    

    public function edit() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $demande = new DemandeService();
            $demandeToEdit = $demande->getOne($id); // Récupère la demande à modifier
            
            if ($demandeToEdit) {
                include __DIR__ . '/../views/front_office.php'; 
                echo "Demande non trouvée.";
            }
        } else {
            echo "Aucun ID de demande spécifié.";
        }
    }
   

    public function update($id, $service_id, $client_id, $description) {
        $demande = new DemandeService();
        $conn = $demande->getConnection(); 
    
        $stmt = $conn->prepare("
            UPDATE " . $demande->getTable() . " 
            SET service_id = ?, client_id = ?, description = ? 
            WHERE id = ?
        ");
    
        $stmt->execute([$service_id, $client_id, $description, $id]);
        header("Location: index.php?action=front_office");
        exit();
    }
    
    public function delete($id) {
        $demande = new DemandeService();
        $conn = $demande->getConnection(); 

        $stmt = $conn->prepare("DELETE FROM " . $demande->getTable() . " WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: index.php?action=front_office");
        exit();
    }
}
?>
