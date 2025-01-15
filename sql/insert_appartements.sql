-- Ajout d'appartements haut de gamme à Lyon
INSERT INTO appartements (photos, categorie, superficie, lits, salle_de_bains, status, description, prix, adresse, ville, pays) VALUES 
('penthouse-luxe.jpg', 'Penthouse Vue Fourvière', '150 m²', 4, 3, 0, 'Exceptionnel penthouse avec terrasse panoramique, prestations luxueuses, ascenseur privatif, cave à vin climatisée, domotique dernière génération', '4500', '10 place Bellecour', 'Lyon', 'France'),
('duplex-croix-rousse.jpg', 'Duplex Canut Rénové', '120 m²', 3, 2, 0, 'Authentique duplex dans immeuble canut, hauteur sous plafond 4m, poutres apparentes, cuisine sur-mesure, vue dégagée sur Lyon', '2800', '25 rue des Tables Claudiennes', 'Lyon', 'France'),
('loft-confluence.jpg', 'Loft Confluence Design', '140 m²', 3, 2, 0, 'Loft d''architecte au cœur de la Confluence, baies vitrées, rooftop privé 40m², parking sécurisé, prestations haut de gamme', '3200', '35 quai Rambaud', 'Lyon', 'France'),
('appartement-foch.jpg', 'T5 Foch Prestige', '160 m²', 4, 3, 0, 'Appartement bourgeois rénové, parquet Versailles, moulures, cheminées, cave à vin, parking, service de conciergerie', '3900', '15 avenue Maréchal Foch', 'Lyon', 'France'),
('studio-opera.jpg', 'Studio Premium Opéra', '40 m²', 1, 1, 0, 'Studio de standing face à l''Opéra, entièrement meublé et équipé, climatisation, domotique, service de conciergerie', '1200', '3 rue de la République', 'Lyon', 'France'),
('t3-brotteaux.jpg', 'T3 Brotteaux Luxe', '90 m²', 2, 2, 0, 'Appartement de prestige quartier Brotteaux, proche parc, cuisine italienne, dressing, cave et parking', '2400', '18 avenue des Brotteaux', 'Lyon', 'France');

-- Ajout de réservations cohérentes
INSERT INTO reservation (Id_appartements, date_depart, date_retour, civilite, firstname, lastname, email, adresse, code_postal, ville, telephone, animaux) VALUES
(6, '2025-02-01', '2025-02-28', 'monsieur', 'Pierre', 'Dubois', 'pierre.dubois@email.com', '28 rue du Commerce', '75015', 'Paris', '0645789632', 'non'),
(12, '2025-01-15', '2025-03-15', 'madame', 'Sophie', 'Martin', 'sophie.martin@email.com', '5 avenue des Ternes', '75017', 'Paris', '0678451236', 'chat'),
(13, '2025-02-10', '2025-04-10', 'monsieur', 'Thomas', 'Bernard', 'thomas.bernard@email.com', '12 rue de la Paix', '69002', 'Lyon', '0712345678', 'non');

-- Ajout de contacts intéressés
INSERT INTO contacts (name, email, phone, message, apartment_id, created_at) VALUES
('Marc Laurent', 'marc.laurent@email.com', '0678912345', 'Très intéressé par ce bien. Possibilité de visite ce week-end ?', 17, CURRENT_TIMESTAMP),
('Claire Dubois', 'claire.dubois@email.com', '0645123789', 'Je souhaiterais des informations supplémentaires sur les prestations incluses.', 6, CURRENT_TIMESTAMP),
('Antoine Moreau', 'antoine.moreau@email.com', '0789456123', 'Disponibilité à partir de mars ? Merci de me recontacter.', 12, CURRENT_TIMESTAMP);

-- Ajout d'utilisateurs actifs
INSERT INTO users (firstname, lastname, email, password, created_at) VALUES
('Philippe', 'Durand', 'philippe.durand@email.com', SHA1('password123'), CURRENT_TIMESTAMP),
('Caroline', 'Leroy', 'caroline.leroy@email.com', SHA1('password456'), CURRENT_TIMESTAMP),
('Laurent', 'Moreau', 'laurent.moreau@email.com', SHA1('password789'), CURRENT_TIMESTAMP);

-- Ajout de favoris
INSERT INTO favoris (user_id, apartment_id, created_at) VALUES
(1, 6, CURRENT_TIMESTAMP),
(1, 12, CURRENT_TIMESTAMP),
(2, 17, CURRENT_TIMESTAMP),
(2, 13, CURRENT_TIMESTAMP),
(3, 5, CURRENT_TIMESTAMP);
