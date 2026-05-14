<?php
$pageTitle = "Contact - TravelToday";
include 'header.php';
require_once 'config.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getConnection();
    
    $nom = $conn->real_escape_string($_POST['nom']);
    $email = $conn->real_escape_string($_POST['email']);
    $sujet = $conn->real_escape_string($_POST['sujet']);
    $message = $conn->real_escape_string($_POST['message']);
    
    $sql = "INSERT INTO contacts (nom, email, sujet, message) VALUES ('$nom', '$email', '$sujet', '$message')";
    
    if ($conn->query($sql)) {
        $success_message = "Your message has been sent successfully! We will respond to you as soon as possible.";
    } else {
        $error_message = "Error occurred while sending the message. Please try again.";
    }
    
    closeConnection($conn);
}
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Contact Us</h1>
            <p>We are here to answer all your questions</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Contact Information</h2>
                    <p>Feel free to contact us for any questions regarding our services, destinations, or to request a customized quote.</p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <h3>Address</h3>
                                <p>Avenue Habib Bourguiba<br>Sousse 4000, Tunisia</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-text">
                                <h3>Phone</h3>
                                <p>+216 24 403 120<br>+216 98 765 432</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <h3>Email</h3>
                                <p>contact@gmail.com<br>info@gmail.com</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-text">
                                <h3>Hours</h3>
                                <p>Mon - Fri: 9am - 6pm<br>Saturday: 9am - 2pm<br>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>

                    <div class="social-media">
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>

                <div class="contact-form-container">
                    <h2>Send Us a Message</h2>
                    
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

                    <form method="POST" action="contact.php" id="contactForm" onsubmit="return validateContactForm()">
                        <div class="form-group">
                            <label for="nom">Full Name *</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="sujet">Subject *</label>
                            <select name="sujet" id="sujet" required>
                                <option value="">-- Select a Subject --</option>
                                    <option value="Demande of info">Ask for info</option>
                                <option value="personalised devis">personalised devis</option>
                                <option value="Modification de réservation">Modification of reservation</option>
                                <option value="Annulation">Annulation</option>
                                <option value="Reclamation">Reclamation</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="6" required placeholder="Describe your request in detail..."></textarea>
                        </div>
                        <button type="submit" class="submit-button">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="map-section">
        <div class="container">
            <h2 class="section-title">Our Location</h2>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d785.6601347862219!2d10.589269836787098!3d35.82637210431539!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2stn!4v1777954368724!5m2!1sen!2stn" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How can i make a reservation ?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>You can make your reservation directly online via our reservation page , Reach us via our phone number, or come visit us at our agency. Our team will guide you through the entire process.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What payment methods do you accept ?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>We accept payments by credit card, bank transfer, and cash. Payment plans in several installments are also available.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Can I cancel or modify my reservation ?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, according to our general terms and conditions. Modifications and cancellations are possible with fees that vary depending on the departure date. Contact us for more details.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Do you offer travel insurance ?</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we offer different travel insurance packages including cancellation, medical assistance, and baggage loss. Our advisors will help you choose the best option.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
