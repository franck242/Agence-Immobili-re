<?php require_once 'includes/header.php'; ?>

<div class="container mt-5 pt-5">
    <h1 class="text-center mb-5">Nos Appartements</h1>

    <!-- Filtres -->
    <div class="search-form mb-5">
        <form id="searchForm" class="p-4 bg-white rounded shadow-sm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Prix maximum</label>
                    <input type="number" name="prix_max" class="form-control" placeholder="Prix max">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Surface minimum</label>
                    <input type="number" name="surface_min" class="form-control" placeholder="Surface min">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Chambres</label>
                    <select name="chambres" class="form-select">
                        <option value="">Tous</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        <?php
                        $types = $connexion->query("SELECT * FROM type_appartements ORDER BY nom")->fetchAll();
                        foreach ($types as $type) {
                            echo '<option value="' . $type['Id_type'] . '">' . htmlspecialchars($type['nom']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Ville</label>
                    <input type="text" name="ville" class="form-control" placeholder="Ville">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des appartements -->
    <div class="row" id="apartmentsList">
        <?php
        require("admin/connexion-bdd.php");
        $requete = $connexion->prepare("SELECT * FROM appartements WHERE status = 0");
        $requete->execute();
        $appartements = $requete->fetchAll(PDO::FETCH_ASSOC);

        foreach ($appartements as $appartement): ?>
            <div class="col-md-4 mb-4">
                <div class="apartment-card">
                    <img src="img/<?php echo htmlspecialchars($appartement['photos']); ?>" 
                         class="apartment-image" 
                         alt="<?php echo htmlspecialchars($appartement['categorie']); ?>">
                    <div class="apartment-details">
                        <h5 class="card-title"><?php echo htmlspecialchars($appartement['categorie']); ?></h5>
                        <p class="apartment-price"><?php echo htmlspecialchars($appartement['prix']); ?> €/mois</p>
                        <div class="apartment-features">
                            <span class="feature-item">
                                <i class="fas fa-ruler-combined"></i> <?php echo htmlspecialchars($appartement['superficie']); ?>
                            </span>
                            <span class="feature-item">
                                <i class="fas fa-bed"></i> <?php echo htmlspecialchars($appartement['lits']); ?> chambres
                            </span>
                            <span class="feature-item">
                                <i class="fas fa-bath"></i> <?php echo htmlspecialchars($appartement['salle_de_bains']); ?> SDB
                            </span>
                        </div>
                        <p class="card-text"><?php echo htmlspecialchars($appartement['description']); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="detail-appartements.php?apt=<?php echo $appartement['Id_appartements']; ?>" 
                               class="btn btn-primary">Voir détails</a>
                            <button class="btn btn-link favorite-btn" 
                                    data-id="<?php echo $appartement['Id_appartements']; ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
