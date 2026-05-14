<?php
$pageTitle = "Reservation - TravelToday";
include 'header.php';
require_once 'config.php';

$conn = getConnection();


$selected_destination = null;
if (isset($_GET['destination_id'])) {
    $destination_id = (int)$_GET['destination_id'];
    $sql = "SELECT * FROM destinations WHERE id = $destination_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $selected_destination = $result->fetch_assoc();
    }
}

//Get all destination
$sql = "SELECT id, nom, pays, prix_depart, promotion, pourcentage_promo FROM destinations ORDER BY nom ASC";
$all_destinations = $conn->query($sql);

//handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $destination_id = (int)$_POST['destination_id'];
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $email = $conn->real_escape_string($_POST['email']);
    $telephone = $conn->real_escape_string($_POST['telephone']);
    $date_depart = $conn->real_escape_string($_POST['date_depart']);
    $date_retour = $conn->real_escape_string($_POST['date_retour']);
    $nombre_passagers = (int)$_POST['nombre_passagers'];
    $type_hebergement = $conn->real_escape_string($_POST['type_hebergement']);
    $message_special = $conn->real_escape_string($_POST['message_special']);
    $prix_total = (float)$_POST['prix_total'];
    
    $sql = "INSERT INTO reservations (destination_id, nom_client, prenom_client, email, telephone, 
            date_depart, date_retour, nombre_passagers, type_hebergement, message_special, prix_total) 
            VALUES ($destination_id, '$nom', '$prenom', '$email', '$telephone', '$date_depart', 
            '$date_retour', $nombre_passagers, '$type_hebergement', '$message_special', $prix_total)";
    
    if ($conn->query($sql)) {
        $reservation_id = $conn->insert_id;
        $success_message = "Your reservation #$reservation_id has been recorded successfully! We will contact you soon.";
    } else {
        $error_message = "Error occurred while recording the reservation. Please try again.";
    }
}
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Book Your Trip</h1>
            <p>Fill out the form below to book your dream vacation</p>
        </div>
    </section>

    <section class="reservation-section">
        <div class="container">
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

            <div class="reservation-container">
                <div class="reservation-form">
                    <h2>Reservation Form</h2>
                    <form method="POST" action="reservation.php" id="reservationForm" onsubmit="return validateForm()">
                        <div class="form-section">
                            <h3><i class="fas fa-map-marked-alt"></i> Destination</h3>
                            <div class="form-group">
                                <label for="destination_id">Choose your destination *</label>
                                <select name="destination_id" id="destination_id" required onchange="updatePrice()">
                                    <option value="">-- Select a destination --</option>
                                    <?php while($dest = $all_destinations->fetch_assoc()): ?>
                                        <option value="<?php echo $dest['id']; ?>" 
                                                data-prix="<?php echo $dest['prix_depart']; ?>"
                                                data-promo="<?php echo $dest['promotion']; ?>"
                                                data-pourcentage="<?php echo $dest['pourcentage_promo']; ?>"
                                                <?php echo ($selected_destination && $selected_destination['id'] == $dest['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($dest['nom']) . ' - ' . htmlspecialchars($dest['pays']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3><i class="fas fa-user"></i> Personal Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="prenom">First Name *</label>
                                    <input type="text" id="prenom" name="prenom" required>
                                </div>
                                <div class="form-group">
                                    <label for="nom">Last Name *</label>
                                    <input type="text" id="nom" name="nom" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="telephone">Phone *</label>
                                    <input type="tel" id="telephone" name="telephone" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3><i class="fas fa-calendar-alt"></i> Dates and Trip Details</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="date_depart">Departure Date *</label>
                                    <input type="date" id="date_depart" name="date_depart" required min="<?php echo date('Y-m-d'); ?>" onchange="updatePrice()">
                                </div>
                                <div class="form-group">
                                    <label for="date_retour">Return Date *</label>
                                    <input type="date" id="date_retour" name="date_retour" required min="<?php echo date('Y-m-d'); ?>" onchange="updatePrice()">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="nombre_passagers">Number of Passengers *</label>
                                    <input type="number" id="nombre_passagers" name="nombre_passagers" min="1" max="10" value="1" required onchange="updatePrice()">
                                </div>
                                <div class="form-group">
                                    <label for="type_hebergement">Type of Accommodation *</label>
                                    <select name="type_hebergement" id="type_hebergement" required onchange="updatePrice()">
                                        <option value="hotel 3*">3-Star Hotel</option>
                                        <option value="hotel 4*">4-Star Hotel</option>
                                        <option value="hotel 5*">5-Star Hotel</option>
                                        <option value="resort">Luxury Resort</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message_special">Special Requests (optional)</label>
                                <textarea id="message_special" name="message_special" rows="4" placeholder="Dietary requirements, accessibility, celebrations, etc."></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="prix_total" id="prix_total" value="0">

                        <button type="submit" class="submit-button">
                            <i class="fas fa-paper-plane"></i> Confirm Reservation
                        </button>
                    </form>
                </div>

                <div class="reservation-summary">
                    <h2>Summary</h2>
                    <div class="summary-content" id="summaryContent">
                        <p class="summary-placeholder">Select a destination to view the summary</p>
                    </div>
                    <div class="total-price" id="totalPrice" style="display: none;">
                        <h3>Prix Total</h3>
                        <p class="price-amount" id="priceAmount">0 TND</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php 
closeConnection($conn);
include 'footer.php'; 
?>
