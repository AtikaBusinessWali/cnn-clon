<?php
require 'db.php';

// Fetch featured articles
$stmtFeatured = $pdo->prepare("SELECT * FROM articles WHERE is_featured=1 ORDER BY published_at DESC LIMIT 3");
$stmtFeatured->execute();
$featuredArticles = $stmtFeatured->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories
$stmtCategories = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
$stmtCategories->execute();
$categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

// Fetch latest articles
$stmtLatest = $pdo->prepare("SELECT * FROM articles ORDER BY published_at DESC LIMIT 10");
$stmtLatest->execute();
$latestArticles = $stmtLatest->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>CNN Clone - Home</title>
<style>
/* Internal CSS - clean responsive layout */
body {
    font-family: Arial, sans-serif;
    margin: 0; padding: 0;
    background: #fff;
    color: #222;
}
header {
    background: #cc0000;
    padding: 15px;
    color: white;
    font-size: 22px;
    font-weight: bold;
    text-align: center;
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
    transition: background 0.3s;
}
nav a:hover {
    background: #cc0000;
}
.container {
    width: 90%;
    max-width: 1100px;
    margin: 20px auto;
}
.featured {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.featured-article {
    flex: 1 1 30%;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgb(0 0 0 / 0.15);
    cursor: pointer;
    transition: transform 0.2s;
    background: #fff;
}
.featured-article:hover {
    transform: scale(1.03);
}
.featured-article img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.featured-article h3 {
    margin: 10px;
    font-size: 18px;
}
.featured-article p {
    margin: 0 10px 10px;
    font-size: 14px;
    color: #555;
}
.categories {
    display: flex;
    justify-content: center;
    margin: 30px 0;
    gap: 15px;
    flex-wrap: wrap;
}
.categories a {
    background: #f4f4f4;
    border: 1px solid #ddd;
    padding: 10px 15px;
    border-radius: 20px;
    text-decoration: none;
    color: #333;
    font-weight: bold;
    transition: background 0.3s;
}
.categories a:hover {
    background: #cc0000;
    color: white;
}
.latest-articles {
    margin-top: 40px;
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
    .featured {
        flex-direction: column;
    }
    .featured-article {
        flex: 1 1 100%;
    }
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
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
            <a href="javascript:void(0)" onclick="goToCategory('<?=htmlspecialchars($cat['slug'])?>')"><?=htmlspecialchars($cat['name'])?></a>
        <?php endforeach; ?>
    <?php else: ?>
        <span style="color:white; padding:12px;">No categories found</span>
    <?php endif; ?>
</nav>
<div class="container">
    <h2>Featured News</h2>
    <div class="featured">
        <?php if (!empty($featuredArticles)): ?>
            <?php foreach ($featuredArticles as $fa): ?>
            <div class="featured-article" onclick="goToArticle('<?=htmlspecialchars($fa['slug'])?>')">
                <?php if ($fa['thumbnail']): ?>
                <img src="<?=htmlspecialchars($fa['thumbnail'])?>" alt="<?=htmlspecialchars($fa['title'])?>" />
                <?php else: ?>
                <img src="https://via.placeholder.com/400x180?text=No+Image" alt="No Image" />
                <?php endif; ?>
                <h3><?=htmlspecialchars($fa['title'])?></h3>
                <p><?=htmlspecialchars($fa['summary'])?></p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No featured news available.</p>
        <?php endif; ?>
    </div>

    <h2>Latest News</h2>
    <div class="latest-articles">
        <?php if (!empty($latestArticles)): ?>
            <?php foreach ($latestArticles as $la): ?>
            <div class="article-summary" onclick="goToArticle('<?=htmlspecialchars($la['slug'])?>')">
                <?php if ($la['thumbnail']): ?>
                <img src="<?=htmlspecialchars($la['thumbnail'])?>" alt="<?=htmlspecialchars($la['title'])?>" />
                <?php else: ?>
                <img src="https://via.placeholder.com/160x90?text=No+Image" alt="No Image" />
                <?php endif; ?>
                <div>
                    <h4><?=htmlspecialchars($la['title'])?></h4>
                    <p><?=htmlspecialchars($la['summary'])?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No latest news available.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
