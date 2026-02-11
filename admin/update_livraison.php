<?php
session_start();
if (!isset($_SESSION['admin_loge'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

if (isset($_GET['id']) && isset($_GET['statut'])) {
    $id = $_GET['id'];
    $nouveau_statut = $_GET['statut'];

    $stmt = $pdo->prepare("UPDATE commandes SET statut_livraison = ? WHERE id = ?");
    $stmt->execute([$nouveau_statut, $id]);

    // On redirige vers le tableau de bord
    header("Location: index.php");
    exit();
}
?>
