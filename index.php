<?php 
include 'includes/db.php'; 
include 'includes/header.php'; 

// --- CONFIGURATION PAGINATION ---
$parPage = 6;
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageActuelle <= 0) $pageActuelle = 1;
$offset = ($pageActuelle - 1) * $parPage;

// Compter le total
$totalProduits = $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
$totalPages = ceil($totalProduits / $parPage);

// Requête avec LIMIT et OFFSET pour la pagination
$query = $pdo->prepare("SELECT * FROM produits ORDER BY id DESC LIMIT :limit OFFSET :offset");
$query->bindValue(':limit', $parPage, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->execute();
?>

<div class="hero-section">
    <h1>LIVRAISON GRATUITE</h1>
    <p>Commandez sur WhatsApp • Payez à la livraison</p>
</div>

<div class="container">
    <div class="product-grid">
        <?php while ($p = $query->fetch()): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php if(!empty($p['image_url'])): ?>
                        <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x200?text=Pas+d'image" alt="Image indisponible">
                    <?php endif; ?>
                    
                    <?php if ($p['stock'] <= 0): ?>
                        <span class="badge-soldout">Rupture</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-content">
                    <h3><?= htmlspecialchars($p['nom']) ?></h3>
                    <p class="product-price"><?= number_format($p['prix'], 2) ?> FGn</p>
                    <p class="shipping-info">✅ Livraison Gratuite</p>
                    
                    <div class="product-footer">
                        <a href="produit_detail.php?id=<?= $p['id'] ?>" class="btn-view">Commander</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if($pageActuelle > 1): ?>
            <a href="index.php?page=<?= $pageActuelle - 1 ?>" class="page-link">« Précédent</a>
        <?php endif; ?>

        <?php for($i=1; $i<=$totalPages; $i++): ?>
            <a href="index.php?page=<?= $i ?>" class="page-link <?= ($i == $pageActuelle) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if($pageActuelle < $totalPages): ?>
            <a href="index.php?page=<?= $pageActuelle + 1 ?>" class="page-link">Suivant »</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
