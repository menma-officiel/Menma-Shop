<?php
include '/includes/db.php';

if (isset($_GET['id']) && isset($_GET['statut'])) {
    $id = $_GET['id'];
    $nouveau_statut = $_GET['statut'];

    $stmt = $pdo->prepare("UPDATE commandes SET statut_livraison = ? WHERE id = ?");
    $stmt->execute([$nouveau_statut, $id]);

    // On redirige vers l'admin après la modification
    header("Location: admin.php");
    exit();
}
?>
