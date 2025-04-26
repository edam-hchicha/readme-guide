<?php
// Inclure les modèles nécessaires
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/DemandeService.php';

$serviceModel = new Service();
$demandeModel = new DemandeService();

// Récupérer tous les services
$services = $serviceModel->getAll();
// Récupérer toutes les demandes
$demandes = $demandeModel->getAll();

// Si une demande est en cours de modification
$demandeToEdit = null;
if (isset($_GET['edit'])) {
    $demandeToEdit = $demandeModel->getOne($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Green House - Services</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/templatemo-woox-travel.css">
    <style>
        .form-container {
            display: none;
        }

        /* Style dynamique pour la section des services */
        .service-card {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .service-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .service-card:hover img {
            transform: scale(1.1);
        }

        .service-content {
            padding: 20px;
            text-align: center;
        }

        .service-content h4 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .service-content p {
            color: #666;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .service-content .btn {
            background-color: #28a745;
            border-color: #28a745;
            transition: background-color 0.3s ease;
        }

        .service-content .btn:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .service-content .price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #28a745;
        }

        .section-heading h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .section-heading p {
            font-size: 1.2rem;
            color: #666;
        }
    </style>
</head>
<body>

<header>
    <div class="container text-center mt-3">
        <img src="http://localhost/eco_tech/assets/images/logo1.png" alt="Green Stay Logo" style="width: 200px;">
    </div>
</header>

<!-- Barre de recherche -->
<div class="container mt-4">
    <input type="text" id="search" class="form-control" placeholder="Rechercher un service...">
</div>

<!-- Section Services -->
<div class="row mt-4" id="service-list">
    <?php foreach ($services as $service): ?>
        <?php if ($service['disponible'] != '1') continue; ?>

        <div class="col-lg-4 col-md-6 mb-4 service-card" data-name="<?= strtolower($service['nom_service']) ?>">
            <div class="card">
                <img src="http://localhost/eco_tech/assets/images/country-01.jpg" alt="Service Image">

                <div class="service-content">
                    <h4><?= htmlspecialchars($service['nom_service']) ?></h4>
                    <p><strong>ID du Service:</strong> <?= $service['id_service'] ?></p>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                    <p class="price"><?= $service['prix_estime'] ?> dt</p>
                    <button class="btn btn-outline-light mt-3" onclick="showForm(<?= $service['id_service'] ?>)">Faire une demande</button>
                </div>

                <!-- Formulaire masqué -->
                <div class="form-container mt-4" id="form-<?= $service['id_service'] ?>">
                    <form method="POST" action="index.php?action=store_demande" class="bg-white p-3 border rounded">
                        <input type="hidden" name="service_id" value="<?= $service['id_service'] ?>">
                        <div class="form-group">
                            <label>ID du Client</label>
                            <input type="text" name="client_id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Envoyer la demande</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Script pour afficher les formulaires -->
<script>
function showForm(serviceId) {
    document.querySelectorAll('.form-container').forEach(form => {
        form.style.display = 'none';
    });

    const form = document.getElementById('form-' + serviceId);
    if (form) {
        form.style.display = 'block';
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Recherche dynamique
document.getElementById('search').addEventListener('input', function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const serviceCards = document.querySelectorAll('#service-list > div');

    serviceCards.forEach(function(card) {
        const serviceName = card.getAttribute('data-name');
        if (serviceName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

<!-- Bouton Afficher mes demandes -->
<div class="container text-center my-4">
    <button id="toggle-demandes" class="btn btn-outline-success">Afficher mes demandes</button>
</div>

<!-- Section Liste des Demandes -->
<section class="section bg-light py-5" id="demande-section" style="display: none;">
  <div class="container">
    <div class="section-heading text-center mb-4">
      <h2>Mes Demandes</h2>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered text-center">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Service</th>
            <th>Description</th>
            <th>Date</th>
            <th>État</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($demandes as $demande): ?>
            <tr>
              <td><?= $demande['id'] ?></td>
              <td><?= $demande['service_id'] ?></td>
              <td><?= htmlspecialchars($demande['description']) ?></td>
              <td><?= $demande['date_demande'] ?></td>
              <td><?= $demande['etat'] ?></td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="toggleEditForm(<?= $demande['id'] ?>)">Modifier</button>
                <a href="index.php?action=delete_demande&id=<?= $demande['id'] ?>" onclick="return confirm('Confirmer la suppression ?')" class="btn btn-danger btn-sm">Supprimer</a>
              </td>
            </tr>

        <!-- Formulaire de modification masqué -->
<tr id="edit-form-<?= $demande['id'] ?>" style="display: none;">
  <td colspan="6">
    <form method="POST" action="index.php?action=update_demande" class="bg-white p-3 border rounded">
      <input type="hidden" name="id" value="<?= $demande['id'] ?>">

      <!-- Champ pour l'ID du service -->
      <div class="form-group">
        <label>Service</label>
        <select name="service_id" class="form-control">
          <?php foreach ($services as $service): ?>
            <option value="<?= $service['id_service'] ?>" <?= $service['id_service'] === $demande['service_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($service['nom_service']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>ID du Client</label>
        <input type="text" name="client_id" class="form-control" value="<?= $demande['client_id'] ?>">
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($demande['description']) ?></textarea>
      </div>

      <!-- Affichage de l'état actuel sans possibilité de modification -->
      <div class="form-group">
        <label>État actuel</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($demande['etat']) ?>" readonly>
        
        <!-- Champ caché pour envoyer l'état -->
        <input type="hidden" name="etat" value="<?= $demande['etat'] ?>">
      </div>

      <button type="submit" class="btn btn-primary">Enregistrer</button>
      <button type="button" class="btn btn-secondary" onclick="toggleEditForm(<?= $demande['id'] ?>)">Annuler</button>
    </form>
  </td>
</tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php if ($demandeToEdit): ?>
<tr>
    <td colspan="6">
        <form method="POST" action="index.php?action=update_demande" class="bg-white p-3 border rounded">
            <input type="hidden" name="id" value="<?= $demandeToEdit['id'] ?>">
            <div class="form-group">
                <label>ID du Service</label>
                <input type="text" name="service_id" class="form-control" value="<?= htmlspecialchars($demandeToEdit['service_id']) ?>" readonly>
            </div>
            <div class="form-group">
                <label>ID du Client</label>
                <input type="text" name="client_id" class="form-control" value="<?= htmlspecialchars($demandeToEdit['client_id']) ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($demandeToEdit['description']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>
    </td>
</tr>
<?php endif; ?>


<script>
document.getElementById('toggle-demandes').addEventListener('click', function () {
    const section = document.getElementById('demande-section');
    section.style.display = (section.style.display === 'none' || section.style.display === '') ? 'block' : 'none';
});

function toggleEditForm(demandeId) {
    const form = document.getElementById('edit-form-' + demandeId);
    if (form) {
        form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'table-row' : 'none';
    }
}
</script>
<script>
function setupFormValidation() {
  const forms = document.querySelectorAll('form[action*="store_demande"], form[action*="update_demande"]');

  forms.forEach(form => {
    const clientId = form.querySelector('[name="client_id"]');
    const description = form.querySelector('[name="description"]');

    // Créer des zones d'erreur sous les champs
    let clientIdError = document.createElement('small');
    clientIdError.style.color = 'red';
    clientId.after(clientIdError);

    let descriptionError = document.createElement('small');
    descriptionError.style.color = 'red';
    description.after(descriptionError);

    form.addEventListener('submit', function (e) {
      let hasError = false;

      // Réinitialiser les erreurs
      clientIdError.textContent = '';
      descriptionError.textContent = '';

      const clientIdValue = clientId.value.trim();
      const descriptionValue = description.value.trim();

      // Vérification ID Client
      if (!clientIdValue) {
        clientIdError.textContent = 'Veuillez entrer votre ID Client.';
        hasError = true;
      } else if (!/^\d+$/.test(clientIdValue) || parseInt(clientIdValue) <= 0) {
        clientIdError.textContent = 'L\'ID Client doit être un entier positif.';
        hasError = true;
      }

      // Vérification Description
      if (!descriptionValue) {
        descriptionError.textContent = 'Veuillez entrer une description.';
        hasError = true;
      } else if (!/[A-Za-zÀ-ÿ]/.test(descriptionValue)) {
        descriptionError.textContent = 'La description doit contenir au moins une lettre.';
        hasError = true;
      }

      if (hasError) {
        e.preventDefault(); // Bloque l'envoi s'il y a des erreurs
      }
    });
  });
}

// Lancer la validation au chargement de la page
document.addEventListener('DOMContentLoaded', setupFormValidation);
</script>

</body>
</html>
