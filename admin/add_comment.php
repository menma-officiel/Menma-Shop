<?php
session_start();
if (!isset($_SESSION['admin_loge'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';
include '/header_admin.php';

// Gestion de l'ajout du commentaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produit = $_POST['id_produit'];
    $nom_client = $_POST['nom_client'];
    $note = $_POST['note'];
    $texte = $_POST['texte'];

    $sql = "INSERT INTO commentaires (id_produit, nom_client, note, texte) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$id_produit, $nom_client, $note, $texte])) {
        echo "<script>alert('Avis ajouté avec succès !'); window.location.href='index.php';</script>";
    }
}
?>

<div class="edit-container">
    <h2 style="border-left: 5px solid #f1c40f;">⭐ Ajouter un avis client</h2>
    <p style="margin-bottom: 20px; color: #666;">Utilisez ce formulaire pour ajouter manuellement des témoignages sur vos produits.</p>

    <form method="POST" class="admin-form">
        <div class="form-row">
            <div>
                <label>Sélectionner le produit</label>
                <select name="id_produit" required style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff;">
                    <?php
                    $produits = $pdo->query("SELECT id, nom FROM produits ORDER BY nom ASC")->fetchAll();
                    foreach($produits as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Note (1 à 5 étoiles)</label>
                <select name="note" required style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff;">
                    <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                    <option value="4">⭐⭐⭐⭐ (Très bien)</option>
                    <option value="3">⭐⭐⭐ (Moyen)</option>
                    <option value="2">⭐⭐ (Bof)</option>
                    <option value="1">⭐ (Mauvais)</option>
                </select>
            </div>
        </div>

        <label>Nom du client</label>
        <input type="text" name="nom_client" placeholder="Ex: Moussa B." required>

        <label>Commentaire / Avis</label>
        <textarea name="texte" rows="4" placeholder="Ex: Produit de très bonne qualité, je recommande !" required></textarea>

        <button type="submit" class="btn-save" style="background: #f1c40f; color: #000;">
            PUBLIER L'AVIS
        </button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
