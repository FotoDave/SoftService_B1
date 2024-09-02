<?php
session_start();
if (isset($_SESSION['email'])) {
    $username = $_SESSION['prenom'];
} else {
    $path = $_SERVER['PHP_SELF'];
    $file = basename($path);
    if ($file !== 'accueil.php') header('location: accueil.php');
    exit;
}
?>