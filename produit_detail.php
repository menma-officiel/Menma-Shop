<?php 
include 'includes/db.php'; 
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) { 
    echo "<div class='container not-found'><h2>Produit introuvable.</h2></div>"; 
    include 'includes/footer.php'; exit; 
}
?>

<div class="container">
    <div class="product-detail-layout">
        
        <div class="product-visuals">
            <div id="main-display" class="main-image">
                <img src="<?= htmlspecialchars($p['image_url']) ?>" id="view-target" class="main-img">
            </div>

            <div class="thumb-list">                <?php 
                // On liste les 5 colonnes d'images possibles
                $colonnes = ['image_url', 'image_url2', 'image_url3', 'image_url4', 'image_url5'];
                foreach($colonnes as $col): 
                    if(!empty($p[$col])): ?>
                        <img src="<?= htmlspecialchars($p[$col]) ?>" 
                             onclick="updateView(this.src)" 
                             class="thumb-item">
                    <?php endif; 
                endforeach; ?>
            </div>
        </div>

        <div class="product-info">
            <span class="badge-shipping">Livraison Gratuite</span>
            <h1 class="product-title"><?= htmlspecialchars($p['nom']) ?></h1>
            <p class="price-big"><?= number_format($p['prix'], 2) ?> FGn</p>
            
            <div class="product-details">
                <h3>Détails du produit</h3>
                <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>
            </div>

            <div class="order-box">
                <h3>Commander ce produit</h3>
                <form onsubmit="event.preventDefault(); sendOrder();">
                    <input type="text" id="nom_client" placeholder="Votre Nom et Prénom" required>
                    <input type="text" id="adresse_client" placeholder="Ville / Quartier de livraison" required>
                    
                    <button type="submit" class="btn-whatsapp">
                         Commander sur WhatsApp
                    </button>
                </form>
                <p class="order-note">Paiement Cash à la livraison</p>
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