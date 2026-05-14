<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="admin-sidebar">
    <nav class="admin-nav">
        <a href="admin_dashboard.php" class="nav-item <?php echo $current_page == 'admin_dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Tableau de Bord</span>
        </a>
        
        <a href="admin_destinations.php" class="nav-item <?php echo $current_page == 'admin_destinations.php' ? 'active' : ''; ?>">
            <i class="fas fa-map-marked-alt"></i>
            <span>Destinations</span>
        </a>
        
        <a href="admin_reservations.php" class="nav-item <?php echo $current_page == 'admin_reservations.php' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-check"></i>
            <span>Réservations</span>
        </a>
        
        <a href="admin_contacts.php" class="nav-item <?php echo $current_page == 'admin_contacts.php' ? 'active' : ''; ?>">
            <i class="fas fa-envelope"></i>
            <span>Messages</span>
        </a>
        
        <div class="nav-divider"></div>
        
        <a href="../index.php" class="nav-item" target="_blank">
            <i class="fas fa-globe"></i>
            <span>Voir le Site</span>
        </a>
        
        <a href="../logout.php" class="nav-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
        </a>
    </nav>
</aside>