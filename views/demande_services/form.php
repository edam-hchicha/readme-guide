<?php
// Vérifier si la variable $demandes est définie et non vide avant de l'utiliser
if (isset($demandes) && !empty($demandes)):
?>
    <h2>Liste des Demandes</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID Service</th>
            <th>Client</th>
            <th>Description</th>
            <th>Date</th>
            <th>État</th>
        </tr>

        <?php foreach ($demandes as $demande): ?>
            <tr>
                <td><?= htmlspecialchars($demande['service_id']) ?></td>
                <td><?= htmlspecialchars($demande['client_id']) ?></td>
                <td><?= htmlspecialchars($demande['description']) ?></td>
                <td><?= htmlspecialchars($demande['date_demande']) ?></td>
                <td><?= htmlspecialchars($demande['etat']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<?php endif; ?>
<?php $isEdit = isset($data); ?>
<h2><?= $isEdit ? "Modifier" : "Faire" ?> une Demande de Service</h2>

<form method="POST" action="index.php?action=<?= $isEdit ? "update_demande&id=" . $data['id'] : "store_demande" ?>">
    
    <!-- Service -->
    <label for="service_id">Service</label><br>
    <input type="text" id="service_id" name="service_id" value="<?= htmlspecialchars($data['service_id'] ?? '') ?>" required><br><br>

    <!-- Client -->
    <label for="client_id">Client</label><br>
    <input type="text" id="client_id" name="client_id" value="<?= htmlspecialchars($data['client_id'] ?? '') ?>" required><br><br>

    <!-- Description -->
    <label for="description">Description</label><br>
    <textarea id="description" name="description" required><?= htmlspecialchars($data['description'] ?? '') ?></textarea><br><br>

    <!-- État -->
    <label for="etat">État</label><br>
    <select id="etat" name="etat" required>
        <option value="1" <?= (isset($data['etat']) && $data['etat'] == 1) ? "selected" : "" ?>>En cours</option>
        <option value="0" <?= (isset($data['etat']) && $data['etat'] == 0) ? "selected" : "" ?>>Terminé</option>
    </select><br><br>

    <!-- Bouton de soumission -->
    <button type="submit"><?= $isEdit ? "Mettre à jour" : "Envoyer" ?></button>
</form>
