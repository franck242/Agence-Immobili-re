USE agence_immobilliere;

-- Mettre à jour le mot de passe admin (le mot de passe est 'admin123' hashé en SHA1)
UPDATE admin 
SET password = '6c7ca345f63f835cb353ff15bd6c5e052ec08e7a'
WHERE email = 'zaylen@gmx.fr';
