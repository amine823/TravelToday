<header class="admin-header">
    <div class="admin-header-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="admin-logo">
            <i class="fas fa-plane-departure"></i>
            <span>VoyageRêve Admin</span>
        </div>
    </div>
    
    <div class="admin-header-right">
        <div class="admin-user">
            <i class="fas fa-user-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['name']); ?></span>
        </div>
        <a href="../logout.php" class="logout-btn" title="Déconnexion">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</header>

<script>
function toggleSidebar() {
    document.querySelector('.admin-sidebar').classList.toggle('active');
}
</script>