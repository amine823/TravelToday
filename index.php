<?php
$pageTitle = "Home - TravelToday";
include 'header.php';
require_once 'config.php';

$conn = getConnection();
$sql = "SELECT * FROM destinations WHERE promotion = TRUE ORDER BY pourcentage_promo DESC LIMIT 3";
$promotions = $conn->query($sql);
closeConnection($conn);
?>

<main>
    <section class="hero-slider">
        <div class="slider-container">
            <div class="slide active" style="background-image: url('images/slider1.jpg');">
                <div class="slide-content">
                    <h2>Discover our destinations</h2>
                    <p>Unforgettable trips at competitive prices</p>
                    <a href="reservation.php" class="cta-button">Book Now</a>
                </div>
            </div>
            <div class="slide" style="background-image: url('images/slider2.jpg');">
                <div class="slide-content">
                    <h2>Explore the world with us</h2>
                    <p>Authentic and memorable experiences</p>
                    <a href="destinations.php" class="cta-button">View Destinations</a>
                </div>
            </div>
            <div class="slide" style="background-image: url('images/slider3.jpg');">
                <div class="slide-content">
                    <h2>Customized Trips</h2>
                    <p>We create your perfect journey together</p>
                    <a href="contact.php" class="cta-button">Contact Us</a>
                </div>
            </div>
        </div>
        <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
        <div class="slider-dots"></div>
    </section>

    <section class="services-section">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Family Trips</h3>
                    <p>Destinations adapted for the whole family with activities for all ages.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Escapades Romantiques</h3>
                    <p>Romantic destination for couples.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-hiking"></i>
                    </div>
                    <h3>Aventure Circuits</h3>
                    <p>Adventure travel for amateur adventurers.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-umbrella-beach"></i>
                    </div>
                    <h3>Vacation beach</h3>
                    <p>Relax and enjoy the beautiful beaches around the world.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <h3>Cultural Discovery</h3>
                    <p>Explore the heritage and culture of fascinating destinations.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-plane"></i>
                    </div>
                    <h3>Vols & Transferts</h3>
                    <p>complete organization of flights and accomodation.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="promotions-section">
        <div class="container">
            <h2 class="section-title">Special Offers</h2>
            <p class="section-subtitle">Take advantage of our exceptional promotions</p>
            <div class="promotions-grid">
                <?php while($promo = $promotions->fetch_assoc()): ?>
                <div class="promo-card">
                    <div class="promo-badge">-<?php echo $promo['pourcentage_promo']; ?>%</div>
                    <div class="promo-image" style="background-image: url('images/<?php echo $promo['image']; ?>');">
                    </div>
                    <div class="promo-content">
                        <h3><?php echo htmlspecialchars($promo['nom']); ?></h3>
                        <p class="promo-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($promo['pays']); ?></p>
                        <p class="promo-description"><?php echo substr(htmlspecialchars($promo['description']), 0, 100) . '...'; ?></p>
                        <div class="promo-price">
                            <span class="old-price"><?php echo number_format($promo['prix_depart'], 2); ?> TND</span>
                            <span class="new-price"><?php echo number_format($promo['prix_depart'] * (1 - $promo['pourcentage_promo']/100), 2); ?> TND</span>
                        </div>
                        <a href="reservation.php?destination_id=<?php echo $promo['id']; ?>" class="promo-button">Book Now</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="why-choose-section">
        <div class="container">
            <h2 class="section-title">Why Choose Us?</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <h3>Expertise</h3>
                    <p>More than 15 years of experience in tourism</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-dollar-sign"></i>
                    <h3>Best Prices</h3>
                    <p>Competitive pricing guaranteed</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-headset"></i>
                    <h3>24/7 Support</h3>
                    <p>Assistance available at any time</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Secure Payment</h3>
                    <p>100% secure transactions</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
