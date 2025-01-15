-- Mise à jour des appartements avec les nouvelles informations
UPDATE appartements SET
    categorie = 'T2 Centre-Ville Nice',
    description = 'Appartement lumineux en plein centre-ville, proche des commerces et transports. Cuisine équipée, séjour avec balcon.',
    prix = 1200,
    superficie = 45,
    ville = 'Nice',
    status = 0
WHERE Id_appartements = 1;

UPDATE appartements SET
    categorie = 'T3 Vue Mer Antibes',
    description = 'Bel appartement avec vue mer, grand séjour, cuisine américaine, deux chambres, parking sécurisé.',
    prix = 1800,
    superficie = 75,
    ville = 'Antibes',
    status = 0
WHERE Id_appartements = 2;

UPDATE appartements SET
    categorie = 'Studio Vieille Ville',
    description = 'Charmant studio rénové dans la vieille ville, proche du port. Cuisine équipée, salle d\'eau moderne.',
    prix = 750,
    superficie = 30,
    ville = 'Nice',
    status = 0
WHERE Id_appartements = 3;

UPDATE appartements SET
    categorie = 'T4 Résidentiel Cannes',
    description = 'Grand appartement familial dans résidence calme avec piscine. Triple exposition, grande terrasse, garage.',
    prix = 2200,
    superficie = 95,
    ville = 'Cannes',
    status = 0
WHERE Id_appartements = 4;

UPDATE appartements SET
    categorie = 'T2 Juan-les-Pins',
    description = 'Appartement à 200m des plages, entièrement rénové. Climatisation, cuisine équipée, parking.',
    prix = 1100,
    superficie = 50,
    ville = 'Juan-les-Pins',
    status = 0
WHERE Id_appartements = 5;

UPDATE appartements SET
    categorie = 'Loft Design Libération',
    description = 'Loft moderne dans ancien atelier rénové. Grandes hauteurs sous plafond, prestations haut de gamme.',
    prix = 1500,
    superficie = 65,
    ville = 'Nice',
    status = 0
WHERE Id_appartements = 6;
