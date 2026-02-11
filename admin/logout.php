<?php
session_start();

// 1. On vide la variable de session
$_SESSION = array();

// 2. On détruit physiquement la session
if (session_id() != "" || isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 2592000, '/');
}

session_destroy();

// 3. On force la redirection vers login.php
// L'utilisation de ./ garantit qu'on reste dans le même dossier
header("Location: ./login.php?logout=1");
exit();
?>
