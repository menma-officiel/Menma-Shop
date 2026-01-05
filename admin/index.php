<?php 
session_start();
if (!isset($_SESSION['admin_loge'])) { header("Location: login.php"); exit(); }
include '../includes/db.php'; 
include __DIR__ . '/../includes/header_admin.php'; 
?>

<div class="edit-container">
    
    <div class="dashboard-header">
        <h2>Tableau de Bord</h2>
        <a href="add_product.php" class="btn-save btn-new-product">
            <i class="fas fa-plus"></i> Nouveau Produit
        </a>
    </div>

    <?php
    // Résumé rapide des statistiques
    $totalProducts = (int) $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
    $totalOrders = (int) $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
    $totalComments = (int) $pdo->query("SELECT COUNT(*) FROM commentaires")->fetchColumn();
    $revenue = (float) $pdo->query("SELECT COALESCE(SUM(p.prix),0) FROM commandes c JOIN produits p ON c.id_produit = p.id")->fetchColumn();
    ?>

    <div class="stats-grid mt-20">
        <div class="stat-card">
            <h4 class="stat-title">Produits</h4>
            <p class="stat-value"><?php echo $totalProducts; ?></p>
        </div>
        <div class="stat-card">
            <h4 class="stat-title">Commandes</h4>
            <p class="stat-value"><?php echo $totalOrders; ?></p>
        </div>
        <div class="stat-card">
            <h4 class="stat-title">Commentaires</h4>
            <p class="stat-value"><?php echo $totalComments; ?></p>
        </div>
        <div class="stat-card">
            <h4 class="stat-title">Chiffre d'affaires</h4>
            <p class="stat-value stat-green"><?php echo number_format($revenue, 0); ?> FGn</p>
        </div>
    </div>

    <div class="admin-card">
        <h3><i class="fas fa-boxes"></i> Stocks</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr class="table-head">
                        <th>Nom</th>
                        <th class="text-center">Prix</th>
                        <th class="text-center">Stock</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll();
                    foreach($produits as $prod): 
                        // SECURITÉ : On vérifie si le nom existe avant de l'afficher
                        $nom = isset($prod['nom']) ? $prod['nom'] : 'Sans nom';
                    ?>
                    <tr>
                        <td><strong><?php echo $nom; ?></strong></td>
                        <td class="price"><strong><?php echo number_format($prod['prix'], 0); ?> FGn</strong></td>
                        <td class="text-center"><?php echo $prod['stock']; ?></td>
                        <td class="text-right">
                            <a href="edit_product.php?id=<?php echo $prod['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="delete_product.php?id=<?php echo $prod['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-card mt-40">
        <h3><i class="fas fa-truck"></i> Commandes</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr class="table-head">
                        <th>Client</th>
                        <th class="text-center">Statut</th>
                        <th class="text-right">Modifier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $commandes = $pdo->query("SELECT * FROM commandes ORDER BY id DESC LIMIT 10")->fetchAll();
                    foreach($commandes as $com): 
                        // SECURITÉ : On vérifie si le client existe
                        $client = isset($com['nom_client']) ? $com['nom_client'] : 'Anonyme';
                    ?>
                    <tr>
                        <td><?php echo $client; ?></td>
                        <td class="text-center">
                            <span class="status-badge"><?php echo $com['statut_livraison']; ?></span>
                        </td>
                        <td class="text-right">
                            <select onchange="window.location.href='update_livraison.php?id=<?php echo $com['id']; ?>&statut='+this.value">
                                <option value="">---</option>
                                <option value="En attente">Attente</option>
                                <option value="Expédié">Parti</option>
                                <option value="Livré">OK</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
