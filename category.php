<?php
require 'db.php';

if (empty($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = $_GET['slug'];

// Fetch category info
$stmtCat = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
$stmtCat->execute([$slug]);
$category = $stmtCat->fetch(PDO::FETCH_ASSOC);
if (!$category) {
    die("Category not found.");
}

// Fetch articles by category
$stmtArticles = $pdo->prepare("SELECT * FROM articles WHERE category_id = ? ORDER BY published_at DESC");
$stmtArticles->execute([$category['id']]);
$articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories list for nav
$stmtCategories = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Category: <?=htmlspecialchars($category['name'])?></title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
body {
    font-family: Arial, sans-serif;
    margin:0;padding:0;
    background:#fff;
    color:#222;
}
header {
    background: #cc0000;
    padding:15px;
    color:white;
    font-size:22px;
    font-weight:bold;
    text-align:center;
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
nav a:hover {background: #cc0000;}
.container {
    width: 90%;
    max-width: 1100px;
    margin: 20px auto;
}
.article-summary {
    border-bottom: 1px solid #ddd;
    padding: 15px 0;
    display: flex;
    gap: 15px;
    cursor: pointer;
}
.article-summary img {
    width: 160px;
    height: 90px;
    object-fit: cover;
    border-radius: 4px;
    flex-shrink: 0;
}
.article-summary div {
    flex: 1;
}
.article-summary h4 {
    margin: 0 0 8px;
    font-size: 18px;
    color: #cc0000;
}
.article-summary p {
    margin: 0;
    color: #555;
    font-size: 14px;
}
@media (max-width: 768px) {
    .article-summary {
        flex-direction: column;
    }
    .article-summary img {
        width: 100%;
        height: 180px;
    }
}
</style>
<script>
function goToArticle(slug) {
    window.location.href = "article.php?slug=" + slug;
}
function goToCategory(slug) {
    window.location.href = "category.php?slug=" + slug;
}
</script>
</head>
<body>
<header>CNN Clone News</header>
<nav>
    <a href="index.php">Home</a>
    <?php foreach ($categories as $cat): ?>
        <a href="javascript:void(0)" onclick="goToCategory('<?=htmlspecialchars($cat['slug'])?>')"><?=htmlspecialchars($cat['name'])?></a>
    <?php endforeach; ?>
</nav>
<div class="container">
    <h2>Category: <?=htmlspecialchars($category['name'])?></h2>
    <?php if(empty($articles)): ?>
        <p>No articles found in this category.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <div class="article-summary" onclick="goToArticle('<?=htmlspecialchars($article['slug'])?>')">
            <?php if ($article['thumbnail']): ?>
            <img src="<?=htmlspecialchars($article['thumbnail'])?>" alt="<?=htmlspecialchars($article['title'])?>" />
            <?php else: ?>
            <img src="https://via.placeholder.com/160x90?text=No+Image" alt="No Image" />
            <?php endif; ?>
            <div>
                <h4><?=htmlspecialchars($article['title'])?></h4>
                <p><?=htmlspecialchars($article['summary'])?></p>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
