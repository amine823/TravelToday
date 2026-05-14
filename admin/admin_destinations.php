<?php
require_once 'admin_check.php';
require_once '../config.php';

$conn = getConnection();
$success_message = '';
$error_message = '';

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM destinations WHERE id = $id";
    if ($conn->query($sql)) {
        $success_message = "Destination supprimée avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nom = $conn->real_escape_string($_POST['nom']);
    $pays = $conn->real_escape_string($_POST['pays']);
    $description = $conn->real_escape_string($_POST['description']);
    $prix_depart = (float)$_POST['prix_depart'];
    $type_voyage = $conn->real_escape_string($_POST['type_voyage']);
    $image = $conn->real_escape_string($_POST['image']);
    $promotion = isset($_POST['promotion']) ? 1 : 0;
    $pourcentage_promo = (int)$_POST['pourcentage_promo'];
    
    if ($id > 0) {
        $sql = "UPDATE destinations SET 
                nom = '$nom', 
                pays = '$pays', 
                description = '$description', 
                prix_depart = $prix_depart, 
                type_voyage = '$type_voyage', 
                image = '$image', 
                promotion = $promotion, 
                pourcentage_promo = $pourcentage_promo 
                WHERE id = $id";
        if ($conn->query($sql)) {
            $success_message = "Destination mise à jour avec succès.";
        } else {
            $error_message = "Erreur lors de la mise à jour.";
        }
    } else {
        $sql = "INSERT INTO destinations (nom, pays, description, prix_depart, type_voyage, image, promotion, pourcentage_promo) 
                VALUES ('$nom', '$pays', '$description', $prix_depart, '$type_voyage', '$image', $promotion, $pourcentage_promo)";
        if ($conn->query($sql)) {
            $success_message = "Destination ajoutée avec succès.";
        } else {
            $error_message = "Erreur lors de l'ajout.";
        }
    }
}

$sql = "SELECT * FROM destinations ORDER BY nom ASC";
$destinations = $conn->query($sql);

$edit_destination = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM destinations WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $edit_destination = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Destinations - Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="page-header">
                <h1>Gestion des Destinations</h1>
                <button onclick="showAddForm()" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter une Destination
                </button>
            </div>
            
            <?php if ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
            <?php endif; ?>
            
            <!-- Add/Edit Form -->
            <div id="destinationForm" class="form-modal" style="<?php echo $edit_destination ? 'display: block;' : 'display: none;'; ?>">
                <div class="form-modal-content">
                    <div class="form-header">
                        <h2><?php echo $edit_destination ? 'Modifier la Destination' : 'Ajouter une Destination'; ?></h2>
                        <button onclick="hideForm()" class="close-btn">&times;</button>
                    </div>
                    
                    <form method="POST" action="admin_destinations.php" class="admin-form">
                        <input type="hidden" name="id" value="<?php echo $edit_destination ? $edit_destination['id'] : ''; ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom">Nom de la Destination *</label>
                                <input type="text" id="nom" name="nom" required value="<?php echo $edit_destination ? htmlspecialchars($edit_destination['nom']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="pays">Pays *</label>
                                <input type="text" id="pays" name="pays" required value="<?php echo $edit_destination ? htmlspecialchars($edit_destination['pays']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" rows="4" required><?php echo $edit_destination ? htmlspecialchars($edit_destination['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="prix_depart">Prix de Départ (TND) *</label>
                                <input type="number" id="prix_depart" name="prix_depart" step="0.01" required value="<?php echo $edit_destination ? $edit_destination['prix_depart'] : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="type_voyage">Type de Voyage *</label>
                                <select id="type_voyage" name="type_voyage" required>
                                    <option value="famille" <?php echo ($edit_destination && $edit_destination['type_voyage'] == 'famille') ? 'selected' : ''; ?>>Famille</option>
                                    <option value="romantique" <?php echo ($edit_destination && $edit_destination['type_voyage'] == 'romantique') ? 'selected' : ''; ?>>Romantique</option>
                                    <option value="aventure" <?php echo ($edit_destination && $edit_destination['type_voyage'] == 'aventure') ? 'selected' : ''; ?>>Aventure</option>
                                    <option value="plage" <?php echo ($edit_destination && $edit_destination['type_voyage'] == 'plage') ? 'selected' : ''; ?>>Plage</option>
                                    <option value="culture" <?php echo ($edit_destination && $edit_destination['type_voyage'] == 'culture') ? 'selected' : ''; ?>>Culture</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="image">Nom du Fichier Image *</label>
                                <input type="text" id="image" name="image" required placeholder="exemple: paris.jpg" value="<?php echo $edit_destination ? htmlspecialchars($edit_destination['image']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="promotion" value="1" <?php echo ($edit_destination && $edit_destination['promotion']) ? 'checked' : ''; ?>>
                                    En Promotion
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="pourcentage_promo">Pourcentage de Promotion (%)</label>
                            <input type="number" id="pourcentage_promo" name="pourcentage_promo" min="0" max="100" value="<?php echo $edit_destination ? $edit_destination['pourcentage_promo'] : '0'; ?>">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                            <button type="button" onclick="hideForm()" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Destinations Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Pays</th>
                            <th>Type</th>
                            <th>Prix</th>
                            <th>Promotion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($dest = $destinations->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $dest['id']; ?></td>
                            <td>
                                <?php echo htmlspecialchars($dest['nom']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($dest['nom']); ?></td>
                            <td><?php echo htmlspecialchars($dest['pays']); ?></td>
                            <td><span class="badge badge-info"><?php echo ucfirst($dest['type_voyage']); ?></span></td>
                            <td><?php echo number_format($dest['prix_depart'], 2); ?> TND</td>
                            <td>
                                <?php if ($dest['promotion']): ?>
                                    <span class="badge badge-warning">-<?php echo $dest['pourcentage_promo']; ?>%</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Non</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="admin_destinations.php?action=edit&id=<?php echo $dest['id']; ?>" class="btn-icon btn-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="admin_destinations.php?action=delete&id=<?php echo $dest['id']; ?>" class="btn-icon btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette destination ?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script>
    function showAddForm() {
        document.getElementById('destinationForm').style.display = 'block';
    }
    
    function hideForm() {
        document.getElementById('destinationForm').style.display = 'none';
        window.location.href = 'admin_destinations.php';
    }
    </script>
</body>
</html>
<?php closeConnection($conn); ?>