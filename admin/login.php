<?php
session_start();

// Si déjà connecté, on va direct à l'index admin
if (isset($_SESSION['admin_loge']) && $_SESSION['admin_loge'] === true) {
    header("Location: index.php");
    exit();
}

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    
    // CHANGEZ LE MOT DE PASSE ICI
    if ($password === "admin123") { 
        $_SESSION['admin_loge'] = true;
        header("Location: index.php");
        exit();
    } else {
        $erreur = "Mot de passe incorrect !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #000000);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .login-card {
            background: rgba(23, 34, 57, 0.53);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 350px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        h2 { margin-bottom: 20px; font-weight: 300; letter-spacing: 2px; }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 20px 0;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #e74c3c;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #c0392b; transform: scale(1.02); }
        .error { color: #ff7675; font-size: 0.9rem; margin-top: 10px; }
        .back-home { margin-top: 20px; display: block; color: rgba(255,255,255,0.5); text-decoration: none; font-size: 0.8rem; }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>🛠️ ADMIN</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="Mot de passe" required autofocus>
            <button type="submit">SE CONNECTER</button>
        </form>
        <?php if ($erreur): ?>
            <p class="error"><?php echo $erreur; ?></p>
        <?php endif; ?>
        <a href="../index.php" class="back-home">← Retour à la boutique</a>
    </div>

</body>
</html>