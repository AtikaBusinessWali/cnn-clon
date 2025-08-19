<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
  header("Location: login.php");
  exit;
}

if (empty($_GET['id'])) {
  die("No article ID specified.");
}

$articleId = intval($_GET['id']);
$adminId = $_SESSION['admin_id'];

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ? AND author_id = ?");
$stmt->execute([$articleId, $adminId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
  die("Article not found or you do not have permission to edit.");
}

// Populate form fields with $article data...
