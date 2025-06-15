<?php
session_start();

require_once "classes/Database.php";
require_once "classes/Product.php";

$database = new Database();
$db = $database->connect();

$product = new Product($db);
$categories = $product->getAllCategories();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Document</title>
<!-- FONTS <-->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
rel="stylesheet"
/>
<!-- ICONS -->
<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
rel="stylesheet"
/>
<!-- STYLES <-->
<link href="assets/css/styles.css" rel="stylesheet" />
<link href="assets/css/index.css" rel="stylesheet" />
</head>
<body>
<?php require "components/header.php"; ?>

<div class="container">
<?php require "components/sidebar.php"; ?>
<div class="content" id="index-content">
<?php if (!isset($_SESSION["user_id"])): ?>
<div class="top-bar">
Get updates & special offers! <a href="register.php">Join Now <i class="fas fa-arrow-right"></i></a>
</div>
<?php else : ?>
<div class="top-bar">
Welcome Back! <?= $_SESSION["firstname"] ?>👋
</div>
<?php endif; ?>

<div class="top-bar" id="top-layer">
<div class="search-bar-content">
<i class="fas fa-magnifying-glass search-icon"></i>
<input type="search" id="search-bar" placeholder="Search...">
<i class="fas fa-filter filter-icon"></i>
</div>
<div class="category-menu" id="category-menu">
    <div class="category-item active">All Categories</div>
    <?php foreach ($categories as $category): ?>
        <div class="category-item"><?= htmlspecialchars($category['name']) ?></div>
    <?php endforeach; ?>
</div>
</div>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>