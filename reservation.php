<?php
session_start();
require_once 'admin/connexion-bdd.php';

// Vérifier si un ID d'appartement est fourni
$id_appartement = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apt'])) {
    $id_appartement = intval($_POST['apt']);
} elseif (isset($_POST['id_appartement'])) {
    $id_appartement = intval($_POST['id_appartement']);
}

if (!$id_appartement) {
    $_SESSION['error'] = "Aucun appartement sélectionné.";
    header('Location: index.php');
    exit();
}

// Récupérer les informations de l'appartement
$requete = $connexion->prepare("
    SELECT a.*, t.nom as type_nom 
    FROM appartements a 
    LEFT JOIN type_appartements t ON a.type_id = t.Id_type 
    WHERE a.Id_appartements = :id AND a.status = 0
");
$requete->execute(['id' => $id_appartement]);
$appartement = $requete->fetch(PDO::FETCH_ASSOC);

if (!$appartement) {
    $_SESSION['error'] = "Appartement non disponible.";
    header('Location: index.php');
    exit();
}

// Traitement du formulaire de réservation final
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
    $errors = [];
    
    // Vérification des dates
    $date_depart = strtotime($_POST['date_depart']);
    $date_retour = strtotime($_POST['date_retour']);
    $aujourd_hui = strtotime('today');
    
    if ($date_depart < $aujourd_hui) {
        $errors[] = "La date de début doit être future.";
    }
    if ($date_retour <= $date_depart) {
        $errors[] = "La date de fin doit être après la date de début.";
    }
    
    // Vérification des autres champs
    if (empty($_POST['civilite'])) $errors[] = "La civilité est requise.";
    if (empty($_POST['firstname'])) $errors[] = "Le prénom est requis.";
    if (empty($_POST['lastname'])) $errors[] = "Le nom est requis.";
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide.";
    }
    if (empty($_POST['adresse'])) $errors[] = "L'adresse est requise.";
    if (empty($_POST['code_postal'])) $errors[] = "Le code postal est requis.";
    if (empty($_POST['ville'])) $errors[] = "La ville est requise.";
    if (empty($_POST['telephone'])) $errors[] = "Le téléphone est requis.";
    
    if (empty($errors)) {
        try {
            $connexion->beginTransaction();
            
            $requete = $connexion->prepare("
                INSERT INTO reservation (
                    Id_appartements, date_depart, date_retour, civilite, 
                    firstname, lastname, email, adresse, code_postal, 
                    ville, telephone, animaux
                ) VALUES (
                    :id_appartement, :date_depart, :date_retour, :civilite,
                    :firstname, :lastname, :email, :adresse, :code_postal,
                    :ville, :telephone, :animaux
                )
            ");
            
            $success = $requete->execute([
                'id_appartement' => $id_appartement,
                'date_depart' => $_POST['date_depart'],
                'date_retour' => $_POST['date_retour'],
                'civilite' => $_POST['civilite'],
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
                'adresse' => $_POST['adresse'],
                'code_postal' => $_POST['code_postal'],
                'ville' => $_POST['ville'],
                'telephone' => $_POST['telephone'],
                'animaux' => !empty($_POST['animaux']) ? $_POST['animaux'] : 'non'
            ]);

            if ($success) {
                // Mise à jour du statut de l'appartement
                $update = $connexion->prepare("UPDATE appartements SET status = 1 WHERE Id_appartements = :id");
                $update->execute(['id' => $id_appartement]);
                
                $connexion->commit();
                
                $_SESSION['success'] = "Votre réservation a été enregistrée avec succès !";
                header('Location: confirmation-reservation.php');
                exit();
            }
        } catch (Exception $e) {
            $connexion->rollBack();
            $errors[] = "Une erreur est survenue lors de la réservation.";
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Réservation de l'appartement</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="apartment-details mb-4">
                        <h4><?php echo htmlspecialchars($appartement['categorie']); ?></h4>
                        <p class="text-muted">
                            <?php echo htmlspecialchars($appartement['adresse']); ?>, 
                            <?php echo htmlspecialchars($appartement['ville']); ?>
                        </p>
                        <p class="price">Prix : <?php echo htmlspecialchars($appartement['prix']); ?> €/mois</p>
                    </div>

                    <form method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="id_appartement" value="<?php echo $id_appartement; ?>">
                        <input type="hidden" name="submit_reservation" value="1">
                        
                        <div class="row g-3">
                            <!-- Dates -->
                            <div class="col-md-6">
                                <label class="form-label">Date d'entrée*</label>
                                <input type="date" name="date_depart" class="form-control" required 
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de sortie*</label>
                                <input type="date" name="date_retour" class="form-control" required
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>

                            <!-- Informations personnelles -->
                            <div class="col-md-4">
                                <label class="form-label">Civilité*</label>
                                <select name="civilite" class="form-select" required>
                                    <option value="">Choisir...</option>
                                    <option value="M.">Monsieur</option>
                                    <option value="Mme">Madame</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Prénom*</label>
                                <input type="text" name="firstname" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nom*</label>
                                <input type="text" name="lastname" class="form-control" required>
                            </div>

                            <!-- Contact -->
                            <div class="col-md-6">
                                <label class="form-label">Email*</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone*</label>
                                <input type="tel" name="telephone" class="form-control" required>
                            </div>

                            <!-- Adresse -->
                            <div class="col-12">
                                <label class="form-label">Adresse*</label>
                                <input type="text" name="adresse" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Code postal*</label>
                                <input type="text" name="code_postal" class="form-control" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Ville*</label>
                                <input type="text" name="ville" class="form-control" required>
                            </div>

                            <!-- Animaux -->
                            <div class="col-12">
                                <label class="form-label">Avez-vous des animaux ?</label>
                                <input type="text" name="animaux" class="form-control" 
                                       placeholder="Si oui, précisez lesquels">
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    Confirmer la réservation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation du formulaire côté client
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Validation des dates
document.addEventListener('DOMContentLoaded', function() {
    const dateDepart = document.querySelector('input[name="date_depart"]');
    const dateRetour = document.querySelector('input[name="date_retour"]');

    dateDepart.addEventListener('change', function() {
        dateRetour.min = this.value;
        if (dateRetour.value && dateRetour.value < this.value) {
            dateRetour.value = this.value;
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
