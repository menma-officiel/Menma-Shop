<?php
include '/includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nouveau_stock = $_POST['nouveau_stock'];

    $stmt = $pdo->prepare("UPDATE produits SET stock = ? WHERE id = ?");
    $stmt->execute([$nouveau_stock, $id]);

    header("Location: admin.php");
    exit();
}
?>
