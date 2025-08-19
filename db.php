<?php
$host = "localhost";
$dbname = "dbggqmpv9kd6sz";
$username = "uar8kmsrlijda";
$password = "knczabmoocaw";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("DB Connection Error: " . $e->getMessage());
    die("Database connection failed.");
}
?>
