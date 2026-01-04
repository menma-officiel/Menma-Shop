<?php
session_start();
if (!isset($_SESSION['admin_loge'])) { header("Location: login.php"); exit; }
include '../includes/db.php';
include '/header_admin.php';

// 1. Calculer le Chiffre d'Affaires et total ventes
$query = $pdo->query("
    SELECT 
        COUNT(c.id) as total_ventes, 
        SUM(p.prix) as chiffre_affaires 
    FROM commandes c 
    JOIN produits p ON c.id_produit = p.id
");
$stats = $query->fetch();

// 2. Récupérer les ventes par produit pour un petit classement
$top_ventes = $pdo->query("
    SELECT p.nom, COUNT(c.id) as nb 
    FROM commandes c 
    JOIN produits p ON c.id_produit = p.id 
    GROUP BY p.nom 
    ORDER BY nb DESC 
    LIMIT 5
")->fetchAll();
?>

<div class="container">
    <h1 style="margin: 20px 0;">Tableau de Bord Financier</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: #7f8c8d;">Ventes Totales</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #3498db;"><?= $stats['total_ventes'] ?? 0 ?></p>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: #7f8c8d;">Revenus Totaux</h3>
            <p style="font-size: 2rem; font-weight: bold; color: #27ae60;"><?= number_format($stats['chiffre_affaires'] ?? 0, 2) ?> FGn</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3>🏆 Top 5 des produits vendus</h3>
            <table style="margin-top: 15px;">
                <?php foreach($top_ventes as $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['nom']) ?></td>
                    <td style="text-align: right; font-weight: bold;"><?= $v['nb'] ?> ventes</td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <p>Besoin d'ajouter du stock ?</p>
            <a href="index.php" class="btn btn-primary" style="margin-top: 10px;">Retour à la gestion</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
