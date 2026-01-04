<?php
session_start();
if (!isset($_SESSION['admin_loge'])) exit;
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];
    $url = $_POST['image_url'];
    $desc = $_POST['description'];

    $sql = "UPDATE produits SET nom=?, prix=?, stock=?, image_url=?, description=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prix, $stock, $url, $desc, $id]);

    header("Location: index.php?success=1");
    exit();
}
?>
