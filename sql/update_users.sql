USE agence_immobilliere;

-- Mise à jour des utilisateurs existants
UPDATE users 
SET firstname = 'Jean', lastname = 'Dupont'
WHERE email = 'jean.dupont@email.com';

UPDATE users 
SET firstname = 'Marie', lastname = 'Martin'
WHERE email = 'marie.martin@email.com';
