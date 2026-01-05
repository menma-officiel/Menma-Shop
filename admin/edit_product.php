<?php
session_start();
// Vérification de sécurité pour l'admin
if (!isset($_SESSION['admin_loge'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';
include __DIR__ . '/../includes/header_admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    // Arrondir le prix pour correspondre au FGn sans décimales
    $prix = (int) round(floatval($_POST['prix']));
    $stock = (int) $_POST['stock'];
    $desc = $_POST['description'];
    $img1 = $_POST['image_url'];
    $img2 = !empty($_POST['image_url2']) ? $_POST['image_url2'] : null;
    $img3 = !empty($_POST['image_url3']) ? $_POST['image_url3'] : null;
    $img4 = !empty($_POST['image_url4']) ? $_POST['image_url4'] : null;
    $img5 = !empty($_POST['image_url5']) ? $_POST['image_url5'] : null;
    $video = !empty($_POST['video_url']) ? $_POST['video_url'] : null;

    // Requête SQL avec les 5 images
    $sql = "INSERT INTO produits (nom, prix, stock, description, image_url, image_url2, image_url3, image_url4, image_url5, video_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prix, $stock, $desc, $img1, $img2, $img3, $img4, $img5, $video]);
    
    header("Location: index.php?success=1");
    exit();
}
?>

<div class="edit-container">
    <h2>➕ Ajouter un Nouveau Produit</h2>
    
    <form method="POST" class="admin-form">
        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="nom" placeholder="Ex: iPhone 15 Pro Max" required>
        </div>

        <div class="form-group-row">
            <div>
                <label>Prix (FGn)</label>
                <input type="number" step="1" name="prix" placeholder="0" required>
            </div>
            <div>
                <label>Quantité en Stock</label>
                <input type="number" name="stock" value="10" required>
            </div>
        </div>

        <div class="form-group">
            <label>Description détaillée</label>
            <textarea name="description" rows="5" placeholder="Décrivez les caractéristiques du produit..."></textarea>
        </div>

        <div class="media-section">
            <h3>🖼️ Galerie de Photos (URLs)</h3>
            <p class="muted">Collez les liens directs de vos images hébergées.</p>
            
            <div class="image-inputs-grid">
                <div class="form-group">
                    <label>Image Principale (Obligatoire)</label>
                    <input type="text" name="image_url" placeholder="https://lien-image-1.jpg" required>
                </div>
                
                <div class="form-group-row">
                    <div>
                        <label>Image 2</label>
                        <input type="text" name="image_url2" placeholder="Lien image 2">
                    </div>
                    <div>
                        <label>Image 3</label>
                        <input type="text" name="image_url3" placeholder="Lien image 3">
                    </div>
                </div>

                <div class="form-group-row">
                    <div>
                        <label>Image 4</label>
                        <input type="text" name="image_url4" placeholder="Lien image 4">
                    </div>
                    <div>
                        <label>Image 5</label>
                        <input type="text" name="image_url5" placeholder="Lien image 5">
                    </div>
                </div>
            </div>

            <div class="form-group mt-15">
                <label class="text-danger">Lien Vidéo</label>
                <input type="text" name="video_url" placeholder="https://www.youtube.com/watch?v=...">
            </div>
        </div>

        <div class="actions-row">
            <button type="submit" class="btn-save flex-2">PUBLIER LE PRODUIT</button>
            <a href="index.php" class="btn-cancel flex-1">ANNULER</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
