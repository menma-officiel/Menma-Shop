<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_p = (int)$_POST['id_produit'];
    $nom = htmlspecialchars($_POST['nom_client']);
    $adr = htmlspecialchars($_POST['adresse']);

    // Début de transaction pour assurer la cohérence des données
    $pdo->beginTransaction();

    try {
        // 1. Vérifier stock
        $st = $pdo->prepare("SELECT stock FROM produits WHERE id = ? FOR UPDATE");
        $st->execute([$id_p]);
        $p = $st->fetch();

        if ($p && $p['stock'] > 0) {
            // 2. Créer la commande
            $ins = $pdo->prepare("INSERT INTO commandes (id_produit, nom_client, adresse_livraison) VALUES (?, ?, ?)");
            $ins->execute([$id_p, $nom, $adr]);

            // 3. Réduire stock
            $upd = $pdo->prepare("UPDATE produits SET stock = stock - 1 WHERE id = ?");
            $upd->execute([$id_p]);

            $pdo->commit();
            header("Location: index.php?success=1");
        } else {
            throw new Exception("Plus de stock");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: index.php?error=stock");
    }
}
