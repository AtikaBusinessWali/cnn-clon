<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login.php");
    exit;
}

// Fetch total articles count
$stmtCount = $pdo->query("SELECT COUNT(*) FROM articles");
$totalArticles = $stmtCount->fetchColumn();

// Fetch categories for nav
$stmtCategories = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
body {
    font-family: Arial, sans-serif;
    margin:0; padding:0;
    background:#fff;
    color:#222;
}
header {
    background: #cc0000;
    padding: 15px;
    text-align: center;
    font-weight: bold;
    color: white;
    font-size: 22px;
}
nav {
    background: #222;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
}
nav a {
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    font-weight: bold;
}
nav a:hover {
    background: #cc0000;
}
.container {
    max-width: 900px;
    margin: 20px auto;
    padding: 10px;
}
.dashboard-links {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
}
.dashboard-links a {
    background: #cc0000;
    color: white;
    padding: 15px 25px;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
    transition: background 0.3s;
}
.dashboard-links a:hover {
    background: #a30000;
}
</style>
</head>
<body>
<header>Admin Dashboard</header>
<nav>
    <a href="index.php">Home</a>
    <?php foreach ($categories as $cat): ?>
        <a href="category.php?slug=<?=htmlspecialchars($cat['slug'])?>"><?=htmlspecialchars($cat['name'])?></a>
    <?php endforeach; ?>
    <a href="logout.php" style="margin-left:auto; background:#555;">Logout</a>
</nav>
<div class="container">
    <h2>Welcome, <?=htmlspecialchars($_SESSION['admin_username'])?></h2>
    <p>Total Articles: <?=$totalArticles?></p>
    <div class="dashboard-links">
        <a href="all-articles.php">Manage Articles</a>
        <a href="my-news.php">My News</a>
        <a href="edit-article.php">Add New Article</a>
    </div>
</div>
</body>
</html>
