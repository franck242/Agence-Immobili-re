-- Mise à jour de la structure de la table users
ALTER TABLE users
    DROP COLUMN IF EXISTS role,
    DROP COLUMN IF EXISTS remember_token,
    DROP COLUMN IF EXISTS created_at,
    DROP COLUMN IF EXISTS updated_at;

-- Ajout des nouvelles colonnes
ALTER TABLE users
    CHANGE COLUMN id Id_users INT AUTO_INCREMENT,
    ADD COLUMN firstname VARCHAR(255) AFTER Id_users,
    ADD COLUMN lastname VARCHAR(255) AFTER firstname;

-- Mise à jour du mot de passe admin en SHA1
UPDATE users SET password = SHA1('admin123') WHERE email = 'admin@example.com';

-- Ajout d'un utilisateur de test
INSERT INTO users (email, password, firstname, lastname) 
VALUES ('test@example.com', SHA1('test123'), 'Test', 'User');
