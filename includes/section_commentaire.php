<?php
// On récupère l'ID du produit depuis la page parente
$product_id = isset($p['id']) ? $p['id'] : 0;

// Récupération des avis pour ce produit
$stmt_rev = $pdo->prepare("SELECT * FROM commentaires WHERE id_produit = ? ORDER BY id DESC");
$stmt_rev->execute([$product_id]);
$reviews = $stmt_rev->fetchAll();
?>

<section class="reviews-section" style="margin-top: 60px; margin-bottom: 60px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
        <h2 style="margin:0; font-size: 1.5rem; color: #2c3e50;">⭐ Avis Clients (<?php echo count($reviews); ?>)</h2>
        <div style="color: #f1c40f; font-weight: bold;">Note moyenne : 5/5</div>
    </div>

    <div style="display: grid; gap: 20px;">
        <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $rev): ?>
                <div class="review-card" style="background: #fff; padding: 25px; border-radius: 15px; border: 1px solid #eaeaea; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: 0.3s;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                        <div>
                            <strong style="font-size: 1.1rem; color: #333; display: block;"><?php echo htmlspecialchars($rev['nom_client']); ?></strong>
                            <span style="font-size: 0.8rem; color: #2ecc71; font-weight: bold;"><i class="fas fa-check-circle"></i> Achat vérifié</span>
                        </div>
                        <div style="color: #f1c40f; font-size: 1.1rem;">
                            <?php 
                                $n = (int)$rev['note'];
                                for($i=1; $i<=5; $i++) echo ($i <= $n) ? '★' : '☆';
                            ?>
                        </div>
                    </div>
                    
                    <p style="color: #555; font-style: italic; line-height: 1.6; font-size: 1rem; margin-bottom: 15px;">
                        "<?php echo nl2br(htmlspecialchars($rev['texte'])); ?>"
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 50px; background: #f9f9f9; border-radius: 20px; border: 2px dashed #ddd;">
                <p style="color: #888; font-size: 1.1rem;">Aucun avis pour le moment. Soyez le premier à commander !</p>
            </div>
        <?php endif; ?>
    </div>
</section>
