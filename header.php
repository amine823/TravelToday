<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Agence de Voyage'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-plane-departure"></i>
                    <h1>TravelToday</h1>
                </div>
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="destinations.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'destinations.php' ? 'active' : ''; ?>">Destinations</a></li>
                        <li><a href="reservation.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reservation.php' ? 'active' : ''; ?>">Reservations</a></li>
                        <li><a href="about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About Us</a></li>
                        <li><a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                        <?php if (!empty($_SESSION['logged_in'])): ?>
                            <li>
                                <span class="user-name">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['name']); ?>
                                </span>
                            </li>
                            <li>
                                <a href="logout.php" class="logout-link">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="login.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>
