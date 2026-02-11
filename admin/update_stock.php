<?php
session_start();
if (!isset($_SESSION['admin_loge'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nouveau_stock = $_POST['nouveau_stock'];

    $stmt = $pdo->prepare("UPDATE produits SET stock = ? WHERE id = ?");
    $stmt->execute([$nouveau_stock, $id]);

    header("Location: index.php");
    exit();
}
?>
