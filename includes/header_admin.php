<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration | Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2b6cb0">
</head>
<body>

<nav class="nav-admin">
    <a href="index.php" class="logo">STORE<span>ADMIN</span></a>
    
    <div class="menu-admin">
        <?php if (isset($_SESSION['admin_username'])): ?>
            <span class="admin-user">Bonjour, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
        <?php endif; ?>

        <a href="index.php" title="Liste des produits"><i class="fas fa-th-list"></i></a>
        <a href="add_product.php" title="Ajouter un produit"><i class="fas fa-plus-circle"></i></a>
        <a href="add_comment.php" title="Ajouter un avis client"><i class="fas fa-star"></i></a>
        <a href="stats.php" title="Statistiques"><i class="fas fa-chart-line"></i></a>
        <a href="../index.php" target="_blank" title="Voir le site public"><i class="fas fa-eye"></i></a>
        <a href="logout.php" class="logout" title="Déconnexion"><i class="fas fa-power-off"></i></a>
    </div>
</nav>

<div class="admin-wrapper">