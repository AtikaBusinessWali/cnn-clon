<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login.php");
    exit;
}

if (empty($_GET['id'])) {
    header("Location: all-articles.php");
    exit;
}

$articleId = intval($_GET['id']);
$adminId = $_SESSION['admin_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ? AND author_id = ?");
    $stmt->execute([$articleId, $adminId]);
} catch (Exception $e) {
    echo "Error deleting article: " . $e->getMessage();
    exit;
}

header("Location: all-articles.php");
exit;
