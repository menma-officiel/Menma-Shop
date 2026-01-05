<?php
session_start();
if (!isset($_SESSION['admin_loge'])) { header("Location: login.php"); exit; }
include '../includes/db.php';
include __DIR__ . '/../includes/header_admin.php';

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
    <h1 class="page-title">Tableau de Bord Financier</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <h3 class="stat-title">Ventes Totales</h3>
            <p class="stat-value stat-blue"><?= $stats['total_ventes'] ?? 0 ?></p>
        </div>
        <div class="stat-card">
            <h3 class="stat-title">Revenus Totaux</h3>
            <p class="stat-value stat-green"><?= number_format($stats['chiffre_affaires'] ?? 0, 0) ?> FGn</p>
        </div>
    </div>

    <div class="top-grid">
        <div class="top-card">
            <h3>🏆 Top 5 des produits vendus</h3>
            <table class="top-table">
                <?php foreach($top_ventes as $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['nom']) ?></td>
                    <td class="text-right font-bold"><?= $v['nb'] ?> ventes</td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="top-card top-card-center">
            <p>Besoin d'ajouter du stock ?</p>
            <a href="index.php" class="btn btn-primary mt-20">Retour à la gestion</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
