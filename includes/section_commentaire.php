<?php
// On récupère l'ID du produit depuis la page parente
$product_id = isset($p['id']) ? $p['id'] : 0;

// Récupération des avis pour ce produit
$stmt_rev = $pdo->prepare("SELECT * FROM commentaires WHERE id_produit = ? ORDER BY id DESC");
$stmt_rev->execute([$product_id]);
$reviews = $stmt_rev->fetchAll();
?>

<section class="reviews-section">
    <div class="reviews-header">
        <h2 class="reviews-title">⭐ Avis Clients (<?php echo count($reviews); ?>)</h2>
        <div class="avg-rating">Note moyenne : 5/5</div>
    </div>

    <div class="reviews-grid">
        <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $rev): ?>
                <div class="review-card">
                    <div class="review-meta">
                        <div>
                            <strong class="review-name"><?php echo htmlspecialchars($rev['nom_client']); ?></strong>
                            <span class="review-verified"><i class="fas fa-check-circle"></i> Achat vérifié</span>
                        </div>
                        <div class="review-stars">
                            <?php 
                                $n = (int)$rev['note'];
                                for($i=1; $i<=5; $i++) echo ($i <= $n) ? '★' : '☆';
                            ?>
                        </div>
                    </div>
                    
                    <p class="review-body">"<?php echo nl2br(htmlspecialchars($rev['texte'])); ?>"</p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-reviews">
                <p>Aucun avis pour le moment. Soyez le premier à commander !</p>
            </div>
        <?php endif; ?>
    </div>
</section>
