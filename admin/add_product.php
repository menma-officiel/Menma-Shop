<?php 
// On remonte d'un dossier pour trouver db.php et header.php
include '../includes/db.php'; 
include __DIR__ . '/../includes/header_admin.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    // Arrondir le prix à l'entier (FGn n'utilise pas de décimales)
    $prix = (int) round(floatval($_POST['prix']));
    $stock = (int) $_POST['stock'];
    $desc = htmlspecialchars($_POST['description']);

    $ins = $pdo->prepare("INSERT INTO produits (nom, prix, stock, description) VALUES (?, ?, ?, ?)");
    $ins->execute([$nom, $prix, $stock, $desc]);
    
    echo "<p class='success-message'>Produit ajouté avec succès !</p>";
}
?>

<h2>Ajouter un nouveau produit</h2>
<form method="POST" class="admin-form">
    <input type="text" name="nom" placeholder="Nom du produit" required><br>
    <input type="number" step="1" name="prix" placeholder="0" required><br>
    <input type="number" name="stock" placeholder="Quantité en stock" required><br>
    <textarea name="description" placeholder="Description du produit"></textarea><br>
    <button type="submit" class="btn-save">Enregistrer le produit</button>
</form>

<?php include '../includes/footer.php'; ?>
