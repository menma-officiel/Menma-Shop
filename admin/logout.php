<?php
session_start();
session_destroy(); // Détruit la connexion
header("Location: login.php");
exit();
?>
