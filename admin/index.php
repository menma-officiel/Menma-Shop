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
    // Correction de la requête SQL pour correspondre à ta base (produit_id)
    $totalProducts = (int) $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
    $totalOrders = (int) $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
    $totalComments = (int) $pdo->query("SELECT COUNT(*) FROM commentaires")->fetchColumn();
    
    // Jointure corrigée : produit_id au lieu de id_produit
    $revenue = (float) $pdo->query("SELECT COALESCE(SUM(p.prix),0) FROM commandes c JOIN produits p ON c.produit_id = p.id")->fetchColumn();
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
            <p class="stat-value stat-green"><?php echo number_format($revenue, 0, ',', ' '); ?> FGn</p>
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
                    // Utilisation de FETCH_ASSOC pour garantir la lecture sur Supabase
                    $query = $pdo->query("SELECT * FROM produits ORDER BY id DESC");
                    $produits = $query->fetchAll(PDO::FETCH_ASSOC);

                    if ($produits):
                        foreach($produits as $prod): 
                            // On garde tes noms mais on ajoute une sécurité pour la casse PostgreSQL
                            $nom = $prod['nom'] ?? $prod['Nom'] ?? 'Sans nom';
                            $prix = $prod['prix'] ?? $prod['Prix'] ?? 0;
                            $stock = $prod['stock'] ?? $prod['Stock'] ?? 0;
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($nom); ?></strong></td>
                        <td class="price text-center"><strong><?php echo number_format($prix, 0, ',', ' '); ?> FGn</strong></td>
                        <td class="text-center"><?php echo $stock; ?></td>
                        <td class="text-right">
                            <a href="edit_product.php?id=<?php echo $prod['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="delete_product.php?id=<?php echo $prod['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; 
                    else: ?>
                        <tr><td colspan="4" class="text-center">Aucun produit trouvé.</td></tr>
                    <?php endif; ?>
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
                    $commandes = $pdo->query("SELECT * FROM commandes ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
                    foreach($commandes as $com): 
                        $client = $com['nom_client'] ?? $com['Nom_client'] ?? 'Anonyme';
                        $statut = $com['statut'] ?? $com['Statut'] ?? 'En attente';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($client); ?></td>
                        <td class="text-center">
                            <span class="status-badge"><?php echo htmlspecialchars($statut); ?></span>
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