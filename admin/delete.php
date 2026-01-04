<?php
session_start();
if (!isset($_SESSION['admin_loge'])) exit;
include '../includes/db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: index.php");
exit();
?>
