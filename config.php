<?php
$host = 'localhost';
$dbname = 'resto';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo"cnx ok";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}