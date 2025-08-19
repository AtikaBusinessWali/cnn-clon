<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login.php");
    exit;
}

$adminId = $_SESSION['admin_id'];

// Fetch articles by this admin (assuming articles table has author_id column)
$stmt = $pdo->prepare("SELECT a.id, a.title, a.published_at, c.name AS category_name FROM articles a JOIN categories c ON a.category_id = c.id WHERE a.author_id = ? ORDER BY published_at DESC");
$stmt->execute([$adminId]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>My News - Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
body { font-family: Arial, sans-serif; margin:0; padding:0; background:#fff; color:#222; }
header { background: #cc0000; padding: 15px; color: white; font-weight: bold; text-align:center; font-size:22px;}
nav { background: #222; display:flex; justify-content:center; flex-wrap:wrap; }
nav a { color: white; padding: 12px 20px; text-decoration:none; font-weight:bold; }
nav a:hover { background: #cc0000; }
.container { max-width: 900px; margin: 20px auto; padding: 10px;}
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
th { background: #cc0000; color: white; }
a.button {
    background:#cc0000;
    color:#fff;
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration:none;
    font-size: 14px;
    margin-right: 5px;
}
a.button:hover {
    background:#a30000;
}
</style>
</head>
<body>
<header>My News - Admin</header>
<nav>
    <a href="admin-dashboard.php">Dashboard</a>
    <a href="edit-article.php">Add New Article</a>
    <a href="logout.php" style="margin-left:auto; background:#555;">Logout</a>
</nav>
<div class="container">
    <?php if(empty($articles)): ?>
        <p>You have not authored any articles.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Published At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
            <tr>
                <td><?=htmlspecialchars($article['title'])?></td>
                <td><?=htmlspecialchars($article['category_name'])?></td>
                <td><?=htmlspecialchars($article['published_at'])?></td>
                <td>
                    <a class="button" href="edit-article.php?id=<?=$article['id']?>">Edit</a>
                    <a class="button" href="delete-article.php?id=<?=$article['id']?>" onclick="return confirm('Delete this article?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
