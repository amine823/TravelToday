<?php
require_once 'admin_check.php';
require_once '../config.php';

$conn = getConnection();
$success_message = '';
$error_message = '';

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM contacts WHERE id = $id";
    if ($conn->query($sql)) {
        $success_message = "Message supprimé avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression.";
    }
}

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['contact_id'];
    $statut = $conn->real_escape_string($_POST['statut']);
    
    $sql = "UPDATE contacts SET statut = '$statut' WHERE id = $id";
    if ($conn->query($sql)) {
        $success_message = "Statut mis à jour avec succès.";
    } else {
        $error_message = "Erreur lors de la mise à jour du statut.";
    }
}
$sql = "SELECT * FROM contacts ORDER BY date_envoi DESC";
$contacts = $conn->query($sql);

$view_contact = null;
if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM contacts WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $view_contact = $result->fetch_assoc();
        
        if ($view_contact['statut'] == 'non lu') {
            $conn->query("UPDATE contacts SET statut = 'lu' WHERE id = $id");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Messages - Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="page-header">
                <h1>Gestion des Messages</h1>
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
            
            <?php if ($view_contact): ?>
            <div class="form-modal" style="display: block;">
                <div class="form-modal-content">
                    <div class="form-header">
                        <h2>Message #<?php echo $view_contact['id']; ?></h2>
                        <a href="admin_contacts.php" class="close-btn">&times;</a>
                    </div>
                    
                    <div class="message-details">
                        <div class="detail-section">
                            <h3><i class="fas fa-user"></i> Informations de l'Expéditeur</h3>
                            <p><strong>Nom:</strong> <?php echo htmlspecialchars($view_contact['nom']); ?></p>
                            <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($view_contact['email']); ?>"><?php echo htmlspecialchars($view_contact['email']); ?></a></p>
                            <p><strong>Date d'envoi:</strong> <?php echo date('d/m/Y H:i', strtotime($view_contact['date_envoi'])); ?></p>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-tag"></i> Sujet</h3>
                            <p><?php echo htmlspecialchars($view_contact['sujet']); ?></p>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-comment"></i> Message</h3>
                            <div class="message-content">
                                <?php echo nl2br(htmlspecialchars($view_contact['message'])); ?>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h3><i class="fas fa-info-circle"></i> Statut</h3>
                            <form method="POST" action="admin_contacts.php" style="display: flex; gap: 1rem; align-items: center;">
                                <input type="hidden" name="contact_id" value="<?php echo $view_contact['id']; ?>">
                                <select name="statut" class="form-control" style="max-width: 200px;">
                                    <option value="non lu" <?php echo $view_contact['statut'] == 'non lu' ? 'selected' : ''; ?>>Non lu</option>
                                    <option value="lu" <?php echo $view_contact['statut'] == 'lu' ? 'selected' : ''; ?>>Lu</option>
                                    <option value="traité" <?php echo $view_contact['statut'] == 'traité' ? 'selected' : ''; ?>>Traité</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="mailto:<?php echo htmlspecialchars($view_contact['email']); ?>?subject=Re: <?php echo urlencode($view_contact['sujet']); ?>" class="btn btn-primary">
                            <i class="fas fa-reply"></i> Répondre par Email
                        </a>
                        <a href="admin_contacts.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Sujet</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($contact = $contacts->fetch_assoc()): ?>
                        <tr class="<?php echo $contact['statut'] == 'non lu' ? 'unread-row' : ''; ?>">
                            <td>#<?php echo $contact['id']; ?></td>
                            <td>
                                <?php if ($contact['statut'] == 'non lu'): ?>
                                    <strong><?php echo htmlspecialchars($contact['nom']); ?></strong>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($contact['nom']); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                            <td><?php echo htmlspecialchars($contact['sujet']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($contact['date_envoi'])); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $contact['statut'] == 'traité' ? 'success' : ($contact['statut'] == 'non lu' ? 'warning' : 'info'); ?>">
                                    <?php echo ucfirst($contact['statut']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="admin_contacts.php?action=view&id=<?php echo $contact['id']; ?>" class="btn-icon btn-view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="admin_contacts.php?action=delete&id=<?php echo $contact['id']; ?>" class="btn-icon btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
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