-- Supprimer l'ancienne contrainte
ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1;

-- Ajouter la bonne contrainte
ALTER TABLE reservation
ADD CONSTRAINT reservation_ibfk_1
FOREIGN KEY (Id_appartements) REFERENCES appartements(Id_appartements)
ON DELETE CASCADE;
