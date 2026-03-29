<?php
// Memulai session untuk autentikasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dummy authentication check - assuming user is logged in for this exercise
// In real scenario, you would redirect to login.php if !isset($_SESSION['user_id'])
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Dummy login
    $_SESSION['username'] = 'admin';
}

$host = 'localhost';
$db   = 'Kampus';
$user = 'root';
$pass = ''; // Sesuaikan dengan password database Anda
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Error logging
    error_log("Database connection failed: " . $e->getMessage());
    die("Koneksi database gagal. Silakan coba lagi nanti.");
}

// Fungsi bantu untuk sanitasi output
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
