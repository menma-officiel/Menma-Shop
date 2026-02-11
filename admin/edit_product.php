<?php
session_start();

// 1. Vérification de sécurité
if (!isset($_SESSION['admin_loge'])) {
    header("Location: login.php");
    exit;
}

// 2. Inclusion de la DB
include '../includes/db.php';
include __DIR__ . '/../includes/header_admin.php';

// 3. Récupération du produit à modifier
if (!isset($_GET['id'])) {
    echo "<p>ID de produit manquant.</p>";
    include __DIR__ . '/../includes/footer_admin.php';
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$prod = $stmt->fetch();

if (!$prod) {
    echo "<p>Produit introuvable.</p>";
    include __DIR__ . '/../includes/footer_admin.php';
    exit;
}

// 4. TRAITEMENT DU FORMULAIRE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prix = (int) round(floatval($_POST['prix']));
    $stock = (int) $_POST['stock'];
    $desc = $_POST['description'];
    $img1 = $_POST['image_url'];
    $img2 = !empty($_POST['image_url2']) ? $_POST['image_url2'] : null;
    $img3 = !empty($_POST['image_url3']) ? $_POST['image_url3'] : null;
    $img4 = !empty($_POST['image_url4']) ? $_POST['image_url4'] : null;
    $img5 = !empty($_POST['image_url5']) ? $_POST['image_url5'] : null;
    $video = !empty($_POST['video_url']) ? $_POST['video_url'] : null;

    // Requête SQL de mise à jour
    $sql = "UPDATE produits SET nom=?, prix=?, stock=?, description=?, image_url=?, image_url2=?, image_url3=?, image_url4=?, image_url5=?, video_url=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([$nom, $prix, $stock, $desc, $img1, $img2, $img3, $img4, $img5, $video, $id]);
        // Rafraîchir les données pour l'affichage
        $prod['nom'] = $nom;
        $prod['prix'] = $prix;
        $prod['stock'] = $stock; 
        // ... etc (ou juste reloader la page)
        echo "<div class='success-message' style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>Produit modifié avec succès ! <a href='index.php'>Retour à la liste</a></div>";
        
        // Re-fetch updated data to show in form
        $stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        $prod = $stmt->fetch();
        
    } catch (PDOException $e) {
        echo "<div class='error-message' style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>Erreur : " . $e->getMessage() . "</div>";
    }
}
?>

<div class="edit-container">
    <h2>✏️ Modifier le Produit</h2>
    <form method="POST" class="admin-form">
        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="nom" value="<?php echo htmlspecialchars($prod['nom']); ?>" required>
        </div>

        <div class="form-group-row">
            <div>
                <label>Prix (FGn)</label>
                <input type="number" step="1" name="prix" value="<?php echo htmlspecialchars($prod['prix']); ?>" required>
            </div>
            <div>
                <label>Quantité en Stock</label>
                <input type="number" name="stock" value="<?php echo htmlspecialchars($prod['stock']); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Description détaillée</label>
            <textarea name="description" rows="5"><?php echo htmlspecialchars($prod['description'] ?? ''); ?></textarea>
        </div>

        <div class="media-section">
            <h3>🖼️ Galerie de Photos (URLs)</h3>
            <div class="image-inputs-grid">
                <div class="form-group">
                    <label>Image Principale (Obligatoire)</label>
                    <input type="text" name="image_url" value="<?php echo htmlspecialchars($prod['image_url']); ?>" required>
                </div>
                <div class="form-group-row">
                    <div>
                        <label>Image 2</label>
                        <input type="text" name="image_url2" value="<?php echo htmlspecialchars($prod['image_url2'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Image 3</label>
                        <input type="text" name="image_url3" value="<?php echo htmlspecialchars($prod['image_url3'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group-row">
                    <div>
                        <label>Image 4</label>
                        <input type="text" name="image_url4" value="<?php echo htmlspecialchars($prod['image_url4'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Image 5</label>
                        <input type="text" name="image_url5" value="<?php echo htmlspecialchars($prod['image_url5'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            <div class="form-group mt-15">
                <label class="text-danger">Lien Vidéo</label>
                <input type="text" name="video_url" placeholder="YouTube URL" value="<?php echo htmlspecialchars($prod['video_url'] ?? ''); ?>">
            </div>
        </div>

        <div class="actions-row">
            <button type="submit" class="btn-save flex-2">ENREGISTRER LES MODIFICATIONS</button>
            <a href="index.php" class="btn-cancel flex-1">ANNULER</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
