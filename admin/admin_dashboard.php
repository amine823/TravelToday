<?php
require_once 'admin_check.php';
require_once '../config.php';

$conn = getConnection();

$sql_destinations = "SELECT COUNT(*) as total FROM destinations";
$total_destinations = $conn->query($sql_destinations)->fetch_assoc()['total'];

$sql_reservations = "SELECT COUNT(*) as total FROM reservations";
$total_reservations = $conn->query($sql_reservations)->fetch_assoc()['total'];

$sql_contacts = "SELECT COUNT(*) as total FROM contacts WHERE statut = 'non lu'";
$total_contacts = $conn->query($sql_contacts)->fetch_assoc()['total'];

$sql_revenue = "SELECT SUM(prix_total) as total FROM reservations WHERE statut != 'annulée'";
$total_revenue = $conn->query($sql_revenue)->fetch_assoc()['total'] ?? 0;

$sql_recent = "SELECT r.*, d.nom as destination_nom FROM reservations r 
               LEFT JOIN destinations d ON r.destination_id = d.id 
               ORDER BY r.date_reservation DESC LIMIT 5";
$recent_reservations = $conn->query($sql_recent);

closeConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - VoyageRêve</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    
    <div class="admin-container">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="admin-content">
            <div class="page-header">
                <h1>Tableau de Bord</h1>
                <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['name']); ?> !</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_destinations; ?></h3>
                        <p>Destinations</p>
                    </div>
                    <a href="admin_destinations.php" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_reservations; ?></h3>
                        <p>Réservations</p>
                    </div>
                    <a href="admin_reservations.php" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $total_contacts; ?></h3>
                        <p>Messages Non Lus</p>
                    </div>
                    <a href="admin_contacts.php" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo number_format($total_revenue, 2); ?> TND</h3>
                        <p>Revenu Total</p>
                    </div>
                    <a href="admin_reservations.php" class="stat-link">Détails <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Réservations Récentes</h2>
                    <a href="admin_reservations.php" class="btn-link">Voir tout</a>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Destination</th>
                                <th>Date Départ</th>
                                <th>Passagers</th>
                                <th>Prix Total</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($res = $recent_reservations->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $res['id']; ?></td>
                                <td><?php echo htmlspecialchars($res['prenom_client'] . ' ' . $res['nom_client']); ?></td>
                                <td><?php echo htmlspecialchars($res['destination_nom']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($res['date_depart'])); ?></td>
                                <td><?php echo $res['nombre_passagers']; ?></td>
                                <td><?php echo number_format($res['prix_total'], 2); ?> TND</td>
                                <td>
                                    <span class="badge badge-<?php echo $res['statut'] == 'confirmée' ? 'success' : ($res['statut'] == 'annulée' ? 'danger' : 'warning'); ?>">
                                        <?php echo ucfirst($res['statut']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="admin_reservations.php?action=view&id=<?php echo $res['id']; ?>" class="btn-icon" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>