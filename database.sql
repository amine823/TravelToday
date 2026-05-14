
CREATE DATABASE IF NOT EXISTS agence_voyage;
USE agence_voyage;

CREATE TABLE IF NOT EXISTS destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    pays VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    prix_depart DECIMAL(10, 2) NOT NULL,
    type_voyage ENUM('famille', 'romantique', 'aventure', 'plage', 'culture') NOT NULL,
    image VARCHAR(255) NOT NULL,
    promotion BOOLEAN DEFAULT FALSE,
    pourcentage_promo INT DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    nom_client VARCHAR(100) NOT NULL,
    prenom_client VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    date_depart DATE NOT NULL,
    date_retour DATE NOT NULL,
    nombre_passagers INT NOT NULL,
    type_hebergement ENUM('hotel 3*', 'hotel 4*', 'hotel 5*', 'resort') NOT NULL,
    message_special TEXT,
    prix_total DECIMAL(10, 2) NOT NULL,
    statut ENUM('en attente', 'confirmée', 'annulée') DEFAULT 'en attente',
    date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destination_id) REFERENCES destinations(id)
);

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('non lu', 'lu', 'traité') DEFAULT 'non lu'
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user'
);
INSERT INTO users (name, email, password, role) VALUES
-- admin1 pass: admin123
('admin1', 'admin@gmail.com', '$2y$12$0wHvUZazjGDt3KcEbNo3IedkTHSRD5JtymrPevHTL7KsmSxy9cU2G', 'admin');

INSERT INTO destinations (nom, pays, description, prix_depart, type_voyage, image, promotion, pourcentage_promo) VALUES
('Paris - La Ville Lumière', 'France', 'Découvrez la capitale française, ses monuments emblématiques, sa gastronomie et son art de vivre. Visitez la Tour Eiffel, le Louvre, et promenez-vous sur les Champs-Élysées.', 450.00, 'romantique', 'paris.jpg', TRUE, 15),
('Maldives - Paradis Tropical', 'Maldives', 'Plongez dans un paradis tropical avec des plages de sable blanc, des eaux cristallines et des resorts de luxe. Idéal pour une lune de miel ou des vacances relaxantes.', 1200.00, 'plage', 'maldives.jpg', TRUE, 20),
('Safari au Kenya', 'Kenya', 'Vivez une aventure inoubliable au cœur de la savane africaine. Observez les Big Five dans leur habitat naturel et découvrez la culture masaï.', 1500.00, 'aventure', 'kenya.jpg', FALSE, 0),
('Rome - La Ville Éternelle', 'Italie', 'Plongez dans l histoire de la Rome antique. Visitez le Colisée, le Vatican, la Fontaine de Trevi et savourez la cuisine italienne authentique.', 380.00, 'culture', 'rome.jpg', FALSE, 0),
('Bali - Ile des Dieux', 'Indonésie', 'Découvrez la beauté exotique de Bali avec ses temples ancestraux, ses rizières en terrasses, ses plages magnifiques et sa culture unique.', 850.00, 'famille', 'bali.jpg', TRUE, 10),
('Santorin - Perle de la Grèce', 'Grèce', 'Admirez les couchers de soleil spectaculaires sur les maisons blanches aux toits bleus. Explorez les villages pittoresques et dégustez les vins locaux.', 680.00, 'romantique', 'santorin.jpg', FALSE, 0),
('Dubai - Luxe et Modernité', 'Émirats Arabes Unis', 'Expérimentez le luxe au sommet du Burj Khalifa, faites du shopping dans des malls gigantesques et vivez des aventures dans le désert.', 950.00, 'famille', 'dubai.jpg', TRUE, 12),
('Marrakech - Ville Impériale', 'Maroc', 'Perdez-vous dans les souks colorés, admirez les palais somptueux et profitez de l hospitalité marocaine dans les riads traditionnels.', 320.00, 'culture', 'marrakech.jpg', FALSE, 0);
