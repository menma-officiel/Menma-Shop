<?php 
session_start();
if (!isset($_SESSION['admin_loge'])) { header("Location: login.php"); exit(); }
include '../includes/db.php'; 
include '/header_admin.php'; 
?>

<div class="edit-container">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="margin:0;">Tableau de Bord</h2>
        <a href="add_product.php" class="btn-save" style="margin:0; padding: 10px 20px; text-decoration:none; font-size:0.9rem; width:auto; display: block;">
            <i class="fas fa-plus"></i> Nouveau Produit
        </a>
    </div>

    <div class="admin-card">
        <h3 style="margin-bottom:20px; color:#2c3e50;"><i class="fas fa-boxes"></i> Stocks</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                        <th style="padding: 12px; text-align: left;">Nom</th>
                        <th style="padding: 12px; text-align: center;">Prix</th>
                        <th style="padding: 12px; text-align: center;">Stock</th>
                        <th style="padding: 12px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $produits = $pdo->query("SELECT * FROM produits ORDER BY id DESC")->fetchAll();
                    foreach($produits as $prod): 
                        // SECURITÉ : On vérifie si le nom existe avant de l'afficher
                        $nom = isset($prod['nom']) ? $prod['nom'] : 'Sans nom';
                    ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;"><strong><?php echo $nom; ?></strong></td>
                        <td style="padding: 12px; text-align: center; color: #2ecc71;"><strong><?php echo $prod['prix']; ?></strong></td>
                        <td style="padding: 12px; text-align: center;"><?php echo $prod['stock']; ?></td>
                        <td style="padding: 12px; text-align: right;">
                            <a href="edit_product.php?id=<?php echo $prod['id']; ?>" style="color: #f39c12; margin-right:10px;"><i class="fas fa-edit"></i></a>
                            <a href="delete_product.php?id=<?php echo $prod['id']; ?>" style="color: #e74c3c;" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-card" style="margin-top: 40px;">
        <h3 style="margin-bottom:20px; color:#2c3e50;"><i class="fas fa-truck"></i> Commandes</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                        <th style="padding: 12px; text-align: left;">Client</th>
                        <th style="padding: 12px; text-align: center;">Statut</th>
                        <th style="padding: 12px; text-align: right;">Modifier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $commandes = $pdo->query("SELECT * FROM commandes ORDER BY id DESC LIMIT 10")->fetchAll();
                    foreach($commandes as $com): 
                        // SECURITÉ : On vérifie si le client existe
                        $client = isset($com['nom_client']) ? $com['nom_client'] : 'Anonyme';
                    ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;"><?php echo $client; ?></td>
                        <td style="padding: 12px; text-align: center;">
                            <span style="background:#3498db; color:white; padding:3px 8px; border-radius:4px; font-size:11px;">
                                <?php echo $com['statut_livraison']; ?>
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: right;">
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

<?php include './footer_admin.php'; ?>
