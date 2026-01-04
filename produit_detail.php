<?php 
include 'includes/db.php'; 
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) { 
    echo "<div class='container' style='padding:80px; text-align:center;'><h2>Produit introuvable.</h2></div>"; 
    include 'includes/footer.php'; exit; 
}
?>

<div class="container">
    <div class="product-detail-layout" style="display: flex; flex-wrap: wrap; gap: 30px; margin-top: 30px;">
        
        <div class="product-visuals" style="flex: 1; min-width: 300px;">
            <div id="main-display" style="width: 100%; height: 400px; background: #f9f9f9; border-radius: 15px; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid #eee;">
                <img src="<?= htmlspecialchars($p['image_url']) ?>" id="view-target" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>

            <div class="thumb-list" style="display: flex; gap: 10px; margin-top: 15px; overflow-x: auto;">
                <?php 
                // On liste les 5 colonnes d'images possibles
                $colonnes = ['image_url', 'image_url2', 'image_url3', 'image_url4', 'image_url5'];
                foreach($colonnes as $col): 
                    if(!empty($p[$col])): ?>
                        <img src="<?= htmlspecialchars($p[$col]) ?>" 
                             onclick="updateView(this.src)" 
                             style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid #ddd;"
                             onmouseover="this.style.borderColor='#25D366'" 
                             onmouseout="this.style.borderColor='#ddd'">
                    <?php endif; 
                endforeach; ?>
            </div>
        </div>

        <div class="product-info" style="flex: 1; min-width: 300px;">
            <span style="background: #e8f5e9; color: #2e7d32; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;">Livraison Gratuite</span>
            <h1 style="margin: 15px 0; font-size: 2.2rem;"><?= htmlspecialchars($p['nom']) ?></h1>
            <p style="font-size: 2rem; color: #27ae60; font-weight: bold; margin-bottom: 20px;"><?= number_format($p['prix'], 2) ?> FGn</p>
            
            <div style="margin-bottom: 30px;">
                <h3 style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Détails du produit</h3>
                <p style="line-height: 1.6; color: #555;"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
            </div>

            <div style="background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-top: 4px solid #25D366;">
                <h3 style="margin-top: 0;">Commander ce produit</h3>
                <form onsubmit="event.preventDefault(); sendOrder();">
                    <input type="text" id="nom_client" placeholder="Votre Nom et Prénom" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;" required>
                    <input type="text" id="adresse_client" placeholder="Ville / Quartier de livraison" style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px;" required>
                    
                    <button type="submit" style="width: 100%; background: #25D366; color: white; border: none; padding: 18px; border-radius: 8px; font-weight: bold; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                         Commander sur WhatsApp
                    </button>
                </form>
                <p style="text-align: center; margin-top: 15px; font-weight: bold; color: #666;">Paiement Cash à la livraison</p>
            </div>
        </div>
    </div>
</div>

<script>
    function updateView(src) {
        document.getElementById('view-target').src = src;
    }

    function sendOrder() {
        const nom = document.getElementById('nom_client').value;
        const adresse = document.getElementById('adresse_client').value;
        const produit = "<?= addslashes($p['nom']) ?>";
        const prix = "<?= $p['prix'] ?> FGn";

        const message = `Bonjour ! Je souhaite commander :
    📦 *Produit :* ${produit}
    💰 *Prix :* ${prix}
    🚚 *Livraison :* GRATUITE
    --------------------------
    👤 *Client :* ${nom}
    📍 *Adresse :* ${adresse}
    --------------------------
    _Paiement à la réception._`;

        window.open(`https://wa.me/224625968097?text=${encodeURIComponent(message)}`, '_blank');
    }
</script>
</div> <div class="container">
    <?php include 'includes/section_commentaire.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>