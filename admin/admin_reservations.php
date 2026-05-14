<?php
require_once 'admin_check.php';
require_once '../config.php';

$conn = getConnection();
$success_message = '';
$error_message = '';

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM reservations WHERE id = $id";
    if ($conn->query($sql)) {
        $success_message = "Réservation supprimée avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression.";
    }
}

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['reservation_id'];
    $statut = $conn->real_escape_string($_POST['statut']);
    
    $sql = "UPDATE reservations SET statut = '$statut' WHERE id = $id";
    if ($conn->query($sql)) {
        $success_message = "Statut mis à jour avec succès.";
    } else {
        $error_message = "Erreur lors de la mise à jour du statut.";
    }
}

$sql = "SELECT r.*, d.nom as destination_nom, d.pays 
        FROM reservations r 
        LEFT JOIN destinations d ON r.destination_id = d.id 
        ORDER BY r.date_reservation DESC";
$reservations = $conn->query($sql);

$view_reservation = null;
if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT r.*, d.nom as destination_nom, d.pays, d.image 
            FROM reservations r 
            LEFT JOIN destinations d ON r.destination_id = d.id 
            WHERE r.id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $view_reservation = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations - Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="page-header">
                <h1>Gestion des Réservations</h1>
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
            
            <!-- View Reservation Modal -->
            <?php if ($view_reservation): ?>
            <div class="form-modal" style="display: block;">
                <div class="form-modal-content" style="max-width: 800px;">
                    <div class="form-header">
                        <h2>Détails de la Réservation #<?php echo $view_reservation['id']; ?></h2>
                        <a href="admin_reservations.php" class="close-btn">&times;</a>
                    </div>
                    
                    <div class="reservation-details">
                        <div class="detail-section">
                            <h3><i class="fas fa-map-marked-alt"></i> Destination</h3>
                            <p><strong><?php echo htmlspecialchars($view_reservation['destination_nom']); ?></strong></p>
                            <p><?php echo htmlspecialchars($view_reservation['pays']); ?></p>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-user"></i> Informations Client</h3>
                            <p><strong>Nom:</strong> <?php echo htmlspecialchars($view_reservation['prenom_client'] . ' ' . $view_reservation['nom_client']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($view_reservation['email']); ?></p>
                            <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($view_reservation['telephone']); ?></p>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-calendar"></i> Dates et Détails</h3>
                            <p><strong>Départ:</strong> <?php echo date('d/m/Y', strtotime($view_reservation['date_depart'])); ?></p>
                            <p><strong>Retour:</strong> <?php echo date('d/m/Y', strtotime($view_reservation['date_retour'])); ?></p>
                            <p><strong>Passagers:</strong> <?php echo $view_reservation['nombre_passagers']; ?> personne(s)</p>
                            <p><strong>Hébergement:</strong> <?php echo htmlspecialchars($view_reservation['type_hebergement']); ?></p>
                        </div>
                        
                        <?php if ($view_reservation['message_special']): ?>
                        <div class="detail-section">
                            <h3><i class="fas fa-comment"></i> Demandes Spéciales</h3>
                            <p><?php echo nl2br(htmlspecialchars($view_reservation['message_special'])); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-dollar-sign"></i> Informations Financières</h3>
                            <p><strong>Prix Total:</strong> <span style="font-size: 1.5rem; color: #f59e0b;"><?php echo number_format($view_reservation['prix_total'], 2); ?> TND</span></p>
                            <p><strong>Date de Réservation:</strong> <?php echo date('d/m/Y H:i', strtotime($view_reservation['date_reservation'])); ?></p>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-info-circle"></i> Statut</h3>
                            <form method="POST" action="admin_reservations.php" style="display: flex; gap: 1rem; align-items: center;">
                                <input type="hidden" name="reservation_id" value="<?php echo $view_reservation['id']; ?>">
                                <select name="statut" class="form-control" style="max-width: 200px;">
                                    <option value="en attente" <?php echo $view_reservation['statut'] == 'en attente' ? 'selected' : ''; ?>>En attente</option>
                                    <option value="confirmée" <?php echo $view_reservation['statut'] == 'confirmée' ? 'selected' : ''; ?>>Confirmée</option>
                                    <option value="annulée" <?php echo $view_reservation['statut'] == 'annulée' ? 'selected' : ''; ?>>Annulée</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="admin_reservations.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Reservations Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Destination</th>
                            <th>Date Départ</th>
                            <th>Date Retour</th>
                            <th>Passagers</th>
                            <th>Prix Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($res = $reservations->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $res['id']; ?></td>
                            <td><?php echo htmlspecialchars($res['prenom_client'] . ' ' . $res['nom_client']); ?></td>
                            <td><?php echo htmlspecialchars($res['destination_nom']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($res['date_depart'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($res['date_retour'])); ?></td>
                            <td><?php echo $res['nombre_passagers']; ?></td>
                            <td><?php echo number_format($res['prix_total'], 2); ?> TND</td>
                            <td>
                                <span class="badge badge-<?php echo $res['statut'] == 'confirmée' ? 'success' : ($res['statut'] == 'annulée' ? 'danger' : 'warning'); ?>">
                                    <?php echo ucfirst($res['statut']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="admin_reservations.php?action=view&id=<?php echo $res['id']; ?>" class="btn-icon btn-view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="admin_reservations.php?action=delete&id=<?php echo $res['id']; ?>" class="btn-icon btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
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
</body>
</html>
<?php closeConnection($conn); ?>