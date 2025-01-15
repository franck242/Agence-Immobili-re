-- Mise à jour des données des appartements
UPDATE appartements SET
    categorie = 'Appartement Luxe Vue Mer',
    description = 'Magnifique appartement avec vue panoramique sur la mer. Prestations haut de gamme, cuisine équipée, terrasse spacieuse.',
    prix = 2500,
    superficie = 120,
    lits = 3,
    salle_de_bains = 2,
    ville = 'Cannes',
    pays = 'France',
    adresse = '123 Boulevard de la Croisette'
WHERE Id_appartements = 1;

UPDATE appartements SET
    categorie = 'Duplex Centre Historique',
    description = 'Charmant duplex au cœur du centre historique. Poutres apparentes, parquet ancien, belle hauteur sous plafond.',
    prix = 1800,
    superficie = 95,
    lits = 2,
    salle_de_bains = 1,
    ville = 'Nice',
    pays = 'France',
    adresse = '45 Rue de la Vieille Ville'
WHERE Id_appartements = 2;

UPDATE appartements SET
    categorie = 'Penthouse Contemporain',
    description = 'Superbe penthouse avec terrasse panoramique. Design moderne, domotique intégrée, prestations luxueuses.',
    prix = 3200,
    superficie = 150,
    lits = 4,
    salle_de_bains = 3,
    ville = 'Antibes',
    pays = 'France',
    adresse = '78 Avenue du Cap'
WHERE Id_appartements = 3;

UPDATE appartements SET
    categorie = 'Studio Premium Bord de Mer',
    description = 'Studio moderne et fonctionnel à 100m de la plage. Entièrement rénové, cuisine équipée, climatisation.',
    prix = 950,
    superficie = 35,
    lits = 1,
    salle_de_bains = 1,
    ville = 'Juan-les-Pins',
    pays = 'France',
    adresse = '15 Promenade du Soleil'
WHERE Id_appartements = 4;

UPDATE appartements SET
    categorie = 'Loft Design Centre-Ville',
    description = 'Loft contemporain en plein centre-ville. Volumes généreux, matériaux nobles, parking sécurisé.',
    prix = 2200,
    superficie = 110,
    lits = 2,
    salle_de_bains = 2,
    ville = 'Cannes',
    pays = 'France',
    adresse = '234 Rue d\'Antibes'
WHERE Id_appartements = 5;

UPDATE appartements SET
    categorie = 'Appartement Familial Résidentiel',
    description = 'Grand appartement familial dans quartier calme. Proche écoles et commerces, jardin commun, cave.',
    prix = 1600,
    superficie = 98,
    lits = 3,
    salle_de_bains = 2,
    ville = 'Nice',
    pays = 'France',
    adresse = '56 Avenue des Fleurs'
WHERE Id_appartements = 6;
