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

$message = "";
$status = "";

$productId = $_GET["id"] ?? ($_POST["product_id"] ?? null);

if (!$productId) {
  header("Location: product.php");
  exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"] ?? "";
  $price = $_POST["price"] ?? "";
  $stock = $_POST["stock"] ?? "";
  $category = $_POST["category"] ?? "";
  $image = $_FILES["image"]["name"];
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["image"]["name"]);
  move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

  if (empty($name) || empty($price) || empty($stock) || empty($category) || empty($image)) {
    $message = "Please fill in all fields.";
    $status = "error";
  } else {
    $database = new Database();
    $db = $database->connect();
    $product = new Product($db);
    if ($product->update($name, $price, $target_file, $stock, $category, $_SESSION["user_id"])) {
      $message = "Product added successful!";
      $status = "success";
    } else {
      $message = "Product added failed.";
      $status = "error";
    }
  }
}


$database = new Database();
$db = $database->connect();

$product = new Product($db);
$categories = $product->getAllCategories();

$existingProduct = $product->getProductById($productId, $_SESSION["user_id"]);
if (!$existingProduct) {
  header("Location: product.php");
  exit();
}
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
<!-- TOASTR -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
<!-- STYLES <-->
<link href="assets/css/styles.css" rel="stylesheet" />
<link href="assets/css/auth.css" rel="stylesheet" />
</head>
<body>
<?php require "components/header.php"; ?>

<div class="container">
<?php require "components/sidebar.php"; ?>
<div class="content" id="auth-content">
<div class="wrapper">
<div class="image-section">
<img src="assets/images/add-product.png" alt="Product image">
</div>
<div class="form-section">
<h1>Update Product</h1>
<p>
Update the details of your existing product
</p>


<form action="update-product.php" method="POST" enctype="multipart/form-data">

<div class="form-group">
<label for="name">
Product Name
</label>
<input type="text" id="name" name="name" value="<?= $existingProduct["name"] ?>">
</div>
<div class="name-holder">
<div class="form-group">
<label for="price">
Price
</label>
<input type="number" id="price" name="price" value="<?= $existingProduct["price"] ?>">
</div>
<div class="form-group">
<label for="stock">
Stock
</label>
<input type="number" id="stock" name="stock" value="<?= $existingProduct["stock"] ?>">
</div>
</div>
<div class="form-group">
<label for="category">
Category
</label>
<div class="input">
<select id="category" name="category">
<?php foreach ($categories as $category): ?>
<option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
<?php endforeach; ?>
</select>
</div>
</div>
<div class="form-group">
<label for="image">
Image
</label>
<input type="file" name="image" id="image" value="<?= $existingProduct["image"] ?>">
</div>
<input type="hidden" name="task_id" value="<?= $productId ?>">
<button type="submit" class="btn-submit">Submit</button>
</form>
</div>
</div>
</div>
<!-- TOASTR -->
<?php if (!empty($message)): ?>
<script>
toastr.options = {
"closeButton": true,
"progressBar": true,
"positionClass": "toast-top-right",
};
toastr.<?= $status ?>("<?= $message ?>");

<?php if ($status == "success"): ?>
setTimeout(function() {
window.location.href = 'index.php';
}, 2000);
<?php endif; ?>
</script>
<?php endif; ?>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>