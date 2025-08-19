<?php
require 'db.php';

if (empty($_GET['slug'])) {
    header("Location: index.php");
    exit;
}

$slug = $_GET['slug'];

// Fetch article
$stmtArticle = $pdo->prepare("SELECT a.*, c.name AS category_name, c.slug AS category_slug FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.slug = ?");
$stmtArticle->execute([$slug]);
$article = $stmtArticle->fetch(PDO::FETCH_ASSOC);
if (!$article) {
    die("Article not found.");
}

// Fetch related articles based on category, excluding current
$stmtRelated = $pdo->prepare("SELECT id, title, slug FROM articles WHERE category_id = ? AND id != ? ORDER BY published_at DESC LIMIT 5");
$stmtRelated->execute([$article['category_id'], $article['id']]);
$relatedArticles = $stmtRelated->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories list for nav
$stmtCategories = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title><?=htmlspecialchars($article['title'])?></title>
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
    font-weight:bold;
    text-align:center;
    font-size: 24px;
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
    width: 90%;
    margin: 20px auto;
}
.article-header {
    margin-bottom: 20px;
}
.article-header h1 {
    margin-bottom: 10px;
    color: #cc0000;
}
.article-meta {
    font-size: 14px;
    color: #555;
    margin-bottom: 25px;
}
.article-thumbnail {
    width: 100%;
    max-height: 400px;
    margin-bottom: 25px;
    object-fit: cover;
    border-radius: 5px;
}
.article-content {
    font-size: 18px;
    line-height: 1.6;
}
.related-articles {
    margin-top: 40px;
    border-top: 1px solid #ddd;
    padding-top: 20px;
}
.related-articles h3 {
    color: #cc0000;
}
.related-articles ul {
    list-style: none;
    padding: 0;
}
.related-articles li {
    margin-bottom: 10px;
    cursor: pointer;
}
.related-articles li:hover {
    text-decoration: underline;
    color: #cc0000;
}
@media (max-width: 768px) {
    .article-content {
        font-size: 16px;
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
    <div class="article-header">
        <h1><?=htmlspecialchars($article['title'])?></h1>
        <div class="article-meta">
            Published on <?=date('F j, Y, g:i a', strtotime($article['published_at']))?> in
            <a href="javascript:void(0)" onclick="goToCategory('<?=htmlspecialchars($article['category_slug'])?>')"><?=htmlspecialchars($article['category_name'])?></a>
        </div>
        <?php if ($article['thumbnail']): ?>
        <img src="<?=htmlspecialchars($article['thumbnail'])?>" alt="<?=htmlspecialchars($article['title'])?>" class="article-thumbnail" />
        <?php endif; ?>
    </div>
    <div class="article-content"><?=nl2br(htmlspecialchars($article['content']))?></div>
    
    <?php if ($relatedArticles): ?>
    <div class="related-articles">
        <h3>Related News</h3>
        <ul>
            <?php foreach ($relatedArticles as $ra): ?>
            <li onclick="goToArticle('<?=htmlspecialchars($ra['slug'])?>')"><?=htmlspecialchars($ra['title'])?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
