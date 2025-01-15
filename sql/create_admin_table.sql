-- Création de la table admin si elle n'existe pas
CREATE TABLE IF NOT EXISTS admin (
    Id_admin INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Suppression de l'ancien admin s'il existe
DELETE FROM admin WHERE email = 'admin@admin.com';

-- Insertion d'un nouvel admin avec mot de passe haché en SHA1
-- Le mot de passe est 'admin123'
INSERT INTO admin (firstname, lastname, email, password) 
VALUES ('Admin', 'System', 'admin@admin.com', SHA1('admin123'));
