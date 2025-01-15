<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer tous les documents avec les détails associés
$requete = $connexion->prepare("
    SELECT d.*,
           a.categorie as appartement_nom,
           CONCAT(u.firstname, ' ', u.lastname) as client_nom,
           u.email as client_email
    FROM documents d
    LEFT JOIN appartements a ON d.appartement_id = a.Id_appartements
    LEFT JOIN users u ON d.user_id = u.Id_users
    ORDER BY d.date_creation DESC
");
$requete->execute();
$documents = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Documents - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .document-icon {
            font-size: 2rem;
            margin-right: 1rem;
        }
        .document-card {
            transition: transform 0.2s;
        }
        .document-card:hover {
            transform: translateY(-5px);
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-file-earmark-text"></i> Gestion des Documents</h1>
                        <p class="text-muted">Gérez vos contrats, factures et autres documents</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-upload"></i> Ajouter un document
                        </button>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Opération réussie !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Filtres -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="filter-form" class="row g-3">
                            <div class="col-md-3">
                                <label for="type" class="form-label">Type de document</label>
                                <select class="form-select" id="type">
                                    <option value="">Tous</option>
                                    <option value="contrat">Contrat</option>
                                    <option value="facture">Facture</option>
                                    <option value="etat_des_lieux">État des lieux</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-select" id="statut">
                                    <option value="">Tous</option>
                                    <option value="brouillon">Brouillon</option>
                                    <option value="final">Final</option>
                                    <option value="archive">Archivé</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label">Rechercher</label>
                                <input type="text" class="form-control" id="search" placeholder="Titre, client...">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Filtrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Liste des documents -->
                <div class="row">
                    <?php foreach ($documents as $doc): ?>
                    <div class="col-md-4 mb-4 document-item" 
                         data-type="<?php echo $doc['type']; ?>"
                         data-statut="<?php echo $doc['statut']; ?>"
                         data-search="<?php echo strtolower($doc['titre'] . ' ' . $doc['client_nom']); ?>">
                        <div class="card h-100 document-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <?php
                                    $icon = '';
                                    switch ($doc['type']) {
                                        case 'contrat':
                                            $icon = 'file-earmark-text';
                                            break;
                                        case 'facture':
                                            $icon = 'file-earmark-spreadsheet';
                                            break;
                                        case 'etat_des_lieux':
                                            $icon = 'file-earmark-check';
                                            break;
                                        default:
                                            $icon = 'file-earmark';
                                    }
                                    ?>
                                    <i class="bi bi-<?php echo $icon; ?> document-icon"></i>
                                    <div>
                                        <h5 class="mb-0"><?php echo htmlspecialchars($doc['titre']); ?></h5>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($doc['date_creation'])); ?>
                                        </small>
                                    </div>
                                </div>

                                <?php
                                $status_class = '';
                                switch ($doc['statut']) {
                                    case 'brouillon':
                                        $status_class = 'bg-warning';
                                        break;
                                    case 'final':
                                        $status_class = 'bg-success';
                                        break;
                                    case 'archive':
                                        $status_class = 'bg-secondary';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $status_class; ?> status-badge">
                                    <?php echo ucfirst($doc['statut']); ?>
                                </span>

                                <?php if ($doc['appartement_nom']): ?>
                                <p class="mb-2">
                                    <i class="bi bi-house"></i> 
                                    <?php echo htmlspecialchars($doc['appartement_nom']); ?>
                                </p>
                                <?php endif; ?>

                                <?php if ($doc['client_nom']): ?>
                                <p class="mb-2">
                                    <i class="bi bi-person"></i>
                                    <?php echo htmlspecialchars($doc['client_nom']); ?>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($doc['client_email']); ?>
                                    </small>
                                </p>
                                <?php endif; ?>

                                <div class="mt-3">
                                    <a href="telecharger-document.php?id=<?php echo $doc['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i> Télécharger
                                    </a>
                                    <?php if ($doc['statut'] === 'brouillon'): ?>
                                    <button class="btn btn-sm btn-outline-success"
                                            onclick="finaliserDocument(<?php echo $doc['id']; ?>)">
                                        <i class="bi bi-check-lg"></i> Finaliser
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="supprimerDocument(<?php echo $doc['id']; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'upload -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="upload-document.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="contrat">Contrat</option>
                                <option value="facture">Facture</option>
                                <option value="etat_des_lieux">État des lieux</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appartement_id" class="form-label">Appartement (optionnel)</label>
                            <select class="form-select" id="appartement_id" name="appartement_id">
                                <option value="">Sélectionner un appartement</option>
                                <?php
                                $requete = $connexion->query("SELECT Id_appartements, categorie FROM appartements ORDER BY categorie");
                                while ($appart = $requete->fetch()) {
                                    echo '<option value="' . $appart['Id_appartements'] . '">' . 
                                         htmlspecialchars($appart['categorie']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Client (optionnel)</label>
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">Sélectionner un client</option>
                                <?php
                                $requete = $connexion->query("SELECT Id_users, firstname, lastname FROM users ORDER BY lastname, firstname");
                                while ($user = $requete->fetch()) {
                                    echo '<option value="' . $user['Id_users'] . '">' . 
                                         htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="document" class="form-label">Fichier</label>
                            <input type="file" class="form-control" id="document" name="document" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Uploader</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrage des documents
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const type = document.getElementById('type').value;
            const statut = document.getElementById('statut').value;
            const search = document.getElementById('search').value.toLowerCase();
            
            document.querySelectorAll('.document-item').forEach(item => {
                let show = true;
                
                if (type && item.dataset.type !== type) {
                    show = false;
                }
                if (statut && item.dataset.statut !== statut) {
                    show = false;
                }
                if (search && !item.dataset.search.includes(search)) {
                    show = false;
                }
                
                item.style.display = show ? 'block' : 'none';
            });
        });

        function finaliserDocument(id) {
            if (confirm('Finaliser ce document ? Cette action est irréversible.')) {
                window.location.href = 'finaliser-document.php?id=' + id;
            }
        }

        function supprimerDocument(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
                window.location.href = 'supprimer-document.php?id=' + id;
            }
        }
    </script>
</body>
</html>
