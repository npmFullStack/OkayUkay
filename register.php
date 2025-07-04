<?php
require_once "classes/Database.php";
require_once "classes/User.php";

$message = "";
$status = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $firstname = $_POST["firstname"] ?? "";
  $lastname = $_POST["lastname"] ?? "";
  $email = $_POST["email"] ?? "";
  $password = $_POST["password"] ?? "";

  if (
    empty($firstname) ||
    empty($lastname) ||
    empty($email) ||
    empty($password)
  ) {
    $message = "Please fill in all fields.";
    $status = "error";
  } else {
    $database = new Database();
    $db = $database->connect();

    $user = new User($db);

    if ($user->register($firstname, $lastname, $email, $password)) {
      $message = "Registration successful!";
      $status = "success";
    } else {
      $message = "Registration failed.";
      $status = "error";
    }
  }
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
<img src="assets/images/register.png" alt="Product image">
</div>
<div class="form-section">
<h1>Sign Up</h1>
<p>
Create your free account
</p>

<form action="register.php" method="POST">

<div class="name-holder">
<div class="form-group">
<label for="firstname">
Firstname
</label>
<input type="text" id="firstname" name="firstname">
</div>
<div class="form-group">
<label for="lastname">
Lastname
</label>


<input type="text" id="lastname" name="lastname">
</div>
</div>
<div class="form-group">
<label for="email">
Email
</label>
<input type="email" id="email" name="email">
</div>
<div class="form-group">
<label for="password">
Password
</label>
<div class="password-wrapper">
<div class="password-toggle">
<i class="fas fa-eye"></i>
</div>
<input type="password" id="password" name="password">
</div>
</div>
<button type="submit" class="btn-submit">Sign up</button>
</form>
<p>
Already have an account? <a href="login.php">Sign in</a>
</p>
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
window.location.href = 'login.php';
}, 2000);
<?php endif; ?>
</script>
<?php endif; ?>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>