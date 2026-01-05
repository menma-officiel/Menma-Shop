<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Redirection si déjà connecté
if (isset($_SESSION['admin_loge']) && $_SESSION['admin_loge'] === true) {
    header("Location: index.php");
    exit();
}

$erreur = "";
$maxAttempts = 5; // nombre max de tentatives
$lockoutMinutes = 15; // durée du blocage après trop d'échecs (minutes)

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $erreur = "Identifiant ou mot de passe incorrect.";
    } else {
        // Récupérer l'admin
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :u LIMIT 1');
        $stmt->execute([':u' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si admin trouvé, gérer verrouillage et vérification
        if ($admin) {
            $locked = false;
            if ($admin['failed_attempts'] >= $maxAttempts && $admin['last_failed_at']) {
                $lastFailed = new DateTime($admin['last_failed_at']);
                $now = new DateTime();
                $diff = $now->getTimestamp() - $lastFailed->getTimestamp();
                if ($diff < ($lockoutMinutes * 60)) {
                    $locked = true;
                }
            }

            if ($locked) {
                $erreur = "Trop de tentatives. Réessayez dans $lockoutMinutes minutes.";
            } else {
                // Vérification du mot de passe
                if (password_verify($password, $admin['password_hash'])) {
                    // Succès : réinitialiser tentatives, régénérer session
                    $reset = $pdo->prepare('UPDATE admins SET failed_attempts = 0, last_failed_at = NULL WHERE id = :id');
                    $reset->execute([':id' => $admin['id']]);

                    session_regenerate_id(true);
                    $_SESSION['admin_loge'] = true;
                    $_SESSION['admin_username'] = $admin['username'];

                    header('Location: index.php');
                    exit();
                } else {
                    // Échec : incrémenter tentatives
                    $upd = $pdo->prepare('UPDATE admins SET failed_attempts = failed_attempts + 1, last_failed_at = now() WHERE id = :id');
                    $upd->execute([':id' => $admin['id']]);
                    $erreur = "Identifiant ou mot de passe incorrect.";
                }
            }
        } else {
            // Ne pas révéler si l'utilisateur n'existe pas
            $erreur = "Identifiant ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="login-page-body">

    <div class="login-card">
        <h2>🛠️ ADMIN</h2>
        <form method="POST" autocomplete="off">
            <div class="field">
                <label for="username">Nom d'utilisateur</label>
                <input id="username" type="text" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" autofocus>
            </div>
            <div class="field">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Se connecter</button>
        </form>
        <?php if ($erreur): ?>
            <p class="error"><?php echo $erreur; ?></p>
        <?php endif; ?>
        <a href="../index.php" class="back-home">← Retour à la boutique</a>
    </div>

    <script src="../assets/js/login.js" defer></script>
</body>
</html>