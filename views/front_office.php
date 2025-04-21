<?php
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/DemandeService.php';

$serviceModel = new Service();
$demandeModel = new DemandeService();

$services = $serviceModel->getAll();
$demandes = $demandeModel->getAll();

$demandeToEdit = null;
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit_demande') {
    $demandeToEdit = $demandeModel->getOne($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Green House</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="eco_tech/assets/css/fontawesome.css">
  <link rel="stylesheet" href="eco_tech/assets/css/templatemo-woox-travel.css">
  <link rel="stylesheet" href="eco_tech/assets/css/owl.css">
  <link rel="stylesheet" href="eco_tech/assets/css/animate.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
</head>
<body>

<header>
  <div class="container text-center mt-3">
    <img src="http://localhost/eco_tech/assets/images/logo1.png" alt="Green Stay Logo" class="logo" style="width: 200px;">
  </div>
</header>

<section id="demande-form" class="contact-us section bg-light py-5">
  <div class="container">
    <div class="section-heading text-center mb-5">
      <h2 class="text-success font-weight-bold">
        <?= $demandeToEdit ? "Modifier une Demande" : "Faire une Demande de Service" ?>
      </h2>
      <p class="text-muted">
        Remplissez les informations ci-dessous pour <?= $demandeToEdit ? "modifier votre demande" : "faire une nouvelle demande" ?>.
      </p>
    </div>

    <!-- Bouton pour afficher / masquer les demandes -->
    <div class="text-center mb-4">
      <button id="toggle-demandes" class="btn btn-outline-success">
        Afficher / Masquer mes demandes
      </button>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="index.php?action=<?= $demandeToEdit ? 'update_demande&id=' . $demandeToEdit['id'] : 'store_demande' ?>" class="main-form bg-white p-4 rounded shadow">
      <div class="row">
        <div class="col-md-6 mb-4">
          <label for="service_id" class="font-weight-bold text-dark">ID du Service</label>
          <input type="text" name="service_id" id="service_id" class="form-control" placeholder="Ex : 3" value="<?= $demandeToEdit ? htmlspecialchars($demandeToEdit['service_id']) : '' ?>">
          <small class="text-danger error-message" style="display:none;">Champ requis ou invalide</small>
        </div>

        <div class="col-md-6 mb-4">
          <label for="client_id" class="font-weight-bold text-dark">ID du Client</label>
          <input type="text" name="client_id" id="client_id" class="form-control" placeholder="Ex : 12" value="<?= $demandeToEdit ? htmlspecialchars($demandeToEdit['client_id']) : '' ?>">
          <small class="text-danger error-message" style="display:none;">Champ requis ou invalide</small>
        </div>

        <div class="col-12 mb-4">
          <label for="description" class="font-weight-bold text-dark">Description</label>
          <textarea name="description" id="description" class="form-control" rows="5" placeholder="Décrivez votre besoin..."><?= $demandeToEdit ? htmlspecialchars($demandeToEdit['description']) : '' ?></textarea>
          <small class="text-danger error-message" style="display:none;">
