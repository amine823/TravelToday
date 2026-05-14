// Hero Slider Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Slider
    const slides = document.querySelectorAll('.slide');
    const sliderDots = document.querySelector('.slider-dots');
    let currentSlide = 0;

    if (slides.length > 0) {
        // Create dots
        slides.forEach((_, index) => {
            const dot = document.createElement('span');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            sliderDots?.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        function goToSlide(n) {
            showSlide(n);
        }

        // Auto slide every 5 seconds
        setInterval(nextSlide, 5000);

        // Navigation buttons
        document.querySelector('.slider-prev')?.addEventListener('click', prevSlide);
        document.querySelector('.slider-next')?.addEventListener('click', nextSlide);
    }

    // Mobile Menu Toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');

    mobileMenuToggle?.addEventListener('click', function() {
        mainNav.classList.toggle('active');
    });

    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Toggle current item
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // Animated Statistics Counter
    const statNumbers = document.querySelectorAll('.stat-number');
    
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60 FPS
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        };

        updateCounter();
    }

    // Intersection Observer for stats animation
    if (statNumbers.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        statNumbers.forEach(stat => observer.observe(stat));
    }
});

// Budget Range Slider Update
function updateBudget(value) {
    document.getElementById('budgetValue').textContent = value + ' TND';
}

// Destination Modal
function showDestinationDetails(destinationId) {
    const modal = document.getElementById('destinationModal');
    const modalContent = document.getElementById('modalContent');
    
    // Fetch destination details via AJAX
    fetch(`get_destination.php?id=${destinationId}`)
        .then(response => response.json())
        .then(data => {
            modalContent.innerHTML = `
                <h2>${data.nom}</h2>
                <img src="images/${data.image}" style="width: 100%; border-radius: 10px; margin-bottom: 1rem;">
                <p><strong>Pays:</strong> ${data.pays}</p>
                <p><strong>Type:</strong> ${data.type_voyage}</p>
                <p><strong>Prix:</strong> ${data.prix_depart} TND</p>
                <p>${data.description}</p>
                <a href="reservation.php?destination_id=${data.id}" class="promo-button" style="margin-top: 1rem;">Réserver Maintenant</a>
            `;
            modal.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = '<p>Erreur lors du chargement des détails.</p>';
        });
}

// Close modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('destinationModal');
    const closeBtn = document.querySelector('.close');
    
    closeBtn?.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});

// Reservation Form Validation
function validateForm() {
    const destination = document.getElementById('destination_id').value;
    const nom = document.getElementById('nom').value.trim();
    const prenom = document.getElementById('prenom').value.trim();
    const email = document.getElementById('email').value.trim();
    const telephone = document.getElementById('telephone').value.trim();
    const dateDepart = document.getElementById('date_depart').value;
    const dateRetour = document.getElementById('date_retour').value;
    const passagers = document.getElementById('nombre_passagers').value;

    if (!destination) {
        alert('Veuillez sélectionner une destination.');
        return false;
    }

    if (!nom || !prenom) {
        alert('Veuillez remplir votre nom complet.');
        return false;
    }

    if (!email || !validateEmail(email)) {
        alert('Veuillez entrer une adresse email valide.');
        return false;
    }

    if (!telephone || telephone.length < 8) {
        alert('Veuillez entrer un numéro de téléphone valide.');
        return false;
    }

    if (!dateDepart || !dateRetour) {
        alert('Veuillez sélectionner les dates de voyage.');
        return false;
    }

    if (new Date(dateRetour) <= new Date(dateDepart)) {
        alert('La date de retour doit être après la date de départ.');
        return false;
    }

    if (passagers < 1) {
        alert('Le nombre de passagers doit être au moins 1.');
        return false;
    }

    return true;
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Update Reservation Price
function updatePrice() {
    const destinationSelect = document.getElementById('destination_id');
    const selectedOption = destinationSelect.options[destinationSelect.selectedIndex];
    
    if (!selectedOption.value) {
        document.getElementById('summaryContent').innerHTML = '<p class="summary-placeholder">Sélectionnez une destination pour voir le récapitulatif</p>';
        document.getElementById('totalPrice').style.display = 'none';
        return;
    }

    const prix = parseFloat(selectedOption.getAttribute('data-prix'));
    const promo = selectedOption.getAttribute('data-promo') === '1';
    const pourcentage = parseFloat(selectedOption.getAttribute('data-pourcentage') || 0);
    
    const dateDepart = document.getElementById('date_depart').value;
    const dateRetour = document.getElementById('date_retour').value;
    const passagers = parseInt(document.getElementById('nombre_passagers').value) || 1;
    const hebergement = document.getElementById('type_hebergement').value;

    // Calculate days
    let days = 1;
    if (dateDepart && dateRetour) {
        const start = new Date(dateDepart);
        const end = new Date(dateRetour);
        days = Math.max(1, Math.ceil((end - start) / (1000 * 60 * 60 * 24)));
    }

    // Calculate base price
    let prixBase = prix;
    if (promo) {
        prixBase = prix * (1 - pourcentage / 100);
    }

    // Add accommodation multiplier
    let hebergementMultiplier = 1;
    switch(hebergement) {
        case 'hotel 3*':
            hebergementMultiplier = 1;
            break;
        case 'hotel 4*':
            hebergementMultiplier = 1.3;
            break;
        case 'hotel 5*':
            hebergementMultiplier = 1.6;
            break;
        case 'resort':
            hebergementMultiplier = 2;
            break;
    }

    const prixTotal = prixBase * days * passagers * hebergementMultiplier;

    // Update hidden input
    document.getElementById('prix_total').value = prixTotal.toFixed(2);

    // Update summary
    const summaryHTML = `
        <div style="margin-bottom: 1rem;">
            <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Destination</h4>
            <p>${selectedOption.text}</p>
        </div>
        ${dateDepart ? `
        <div style="margin-bottom: 1rem;">
            <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Dates</h4>
            <p>Du ${formatDate(dateDepart)} au ${formatDate(dateRetour)}</p>
            <p><strong>${days} jour(s)</strong></p>
        </div>
        ` : ''}
        <div style="margin-bottom: 1rem;">
            <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Passagers</h4>
            <p>${passagers} personne(s)</p>
        </div>
        <div style="margin-bottom: 1rem;">
            <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Hébergement</h4>
            <p>${hebergement}</p>
        </div>
    `;

    document.getElementById('summaryContent').innerHTML = summaryHTML;
    document.getElementById('totalPrice').style.display = 'block';
    document.getElementById('priceAmount').textContent = prixTotal.toFixed(2) + ' TND';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    return date.toLocaleDateString('fr-FR', options);
}

// Contact Form Validation
function validateContactForm() {
    const nom = document.getElementById('nom').value.trim();
    const email = document.getElementById('email').value.trim();
    const sujet = document.getElementById('sujet').value;
    const message = document.getElementById('message').value.trim();

    if (!nom) {
        alert('Veuillez entrer votre nom.');
        return false;
    }

    if (!email || !validateEmail(email)) {
        alert('Veuillez entrer une adresse email valide.');
        return false;
    }

    if (!sujet) {
        alert('Veuillez sélectionner un sujet.');
        return false;
    }

    if (!message || message.length < 10) {
        alert('Veuillez entrer un message d\'au moins 10 caractères.');
        return false;
    }

    return true;
}
