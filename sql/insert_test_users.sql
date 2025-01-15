-- Insertion des utilisateurs de test
INSERT INTO users (email, password, role) VALUES
('jean.dupont@email.com', SHA1('password123'), 'user'),
('marie.martin@email.com', SHA1('password456'), 'user');
