USE agence_immobilliere;

-- Création de la table type_appartements
CREATE TABLE IF NOT EXISTS type_appartements (
    Id_type INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    description TEXT
);

-- Insertion des types de base
INSERT INTO type_appartements (nom, description) VALUES
('Studio', 'Appartement d''une seule pièce avec cuisine et salle de bain'),
('T1', 'Appartement avec une chambre séparée'),
('T2', 'Appartement avec deux pièces principales'),
('T3', 'Appartement avec trois pièces principales'),
('T4', 'Appartement avec quatre pièces principales'),
('T5', 'Appartement avec cinq pièces principales'),
('Duplex', 'Appartement sur deux niveaux'),
('Loft', 'Grand espace ouvert aménagé'),
('Penthouse', 'Appartement de luxe au dernier étage');
