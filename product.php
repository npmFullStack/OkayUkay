<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

require_once "classes/Database.php";
require_once "classes/Product.php";

$database = new Database();
$db = $database->connect();

$product = new Product($db);
$products = $product->fetchProducts($_SESSION["user_id"]);
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
<div class="top-bar" id="top-layer">
<div class="search-bar-content">
<i class="fas fa-magnifying-glass search-icon"></i>
<input type="search" id="search-bar" placeholder="Search...">
<i class="fas fa-filter filter-icon"></i>
</div>
<a href="add-product.php" class="btn-add" id="desktop-add-btn">Add Product</a>
<div class="category-menu" id="category-menu">
<div class="category-item active">
All Categories
</div>
<?php foreach ($categories as $category): ?>
<div class="category-item">
<?= htmlspecialchars($category['name']) ?>
</div>
<?php endforeach; ?>
</div>
</div>
<a href="add-product.php" class="btn-add" id="mobile-add-btn"><i class="fas fa-plus"></i></a>

<div class="card-holder">
<?php foreach ($products as $product): ?>
<div class="card">
<div class="card-body">
<img src="<?= $product["image"] ?>" alt="Product Image">
<i class="fas fa-ellipsis-h dropdown-toggle"></i>
<div class="dropdown-menu">
<ul>
<li><a href="view-product.php?id=<?= $product["id"] ?>">View</a></li>
<li><a href="update-product.php?id=<?= $product["id"] ?>">Update</a></li>
<li><a href="delete-product.php?id=<?= $product["id"] ?>">Delete</a></li>
</ul>
</div>

</div>
<div class="card-footer">
<span class="product-name">
<?= $product["name"] ?>
</span>
<span class="category-name">
<?= $product["category_name"] ?? $category["name"] ?? "Uncategorized" ?>
</span>
</div>
<div class="card-footer">
<span class="product-stock">
Stock Left: <strong><?= $product["stock"] ?></strong>
</span>
<span class="product-price">
₱<?= $product["price"] ?>
</span>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>