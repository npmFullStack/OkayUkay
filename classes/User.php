<?php
require_once "Database.php";
class User
{
 private $db;
 private $table = "users";

 public function __construct($db)
 {
  $database = new Database();
  $this->db = $database->connect();
 }

 public function register($firstname, $lastname, $email, $password)
 {
  try {
   $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

   $sql = "INSERT INTO $this->table (firstname, lastname, email, password) 
                VALUES (:firstname, :lastname, :email, :password)";
   $stmt = $this->db->prepare($sql);
   $stmt->bindParam(":firstname", $firstname);
   $stmt->bindParam(":lastname", $lastname);
   $stmt->bindParam(":email", $email);
   $stmt->bindParam(":password", $hashedPassword);

   return $stmt->execute();
  } catch (PDOException $e) {
   error_log("Registration Error: " . $e->getMessage());
   return false;
  }
 }

 public function login($email, $password)
 {
  try {
   $sql = "SELECT * FROM $this->table WHERE email = ? LIMIT 1";
   $stmt = $this->db->prepare($sql);
   $stmt->execute([$email]);
   $user = $stmt->fetch(PDO::FETCH_ASSOC);
   if ($user && password_verify($password, $user["password"])) {
    unset($user["password"]);
    return $user;
   }
  } catch (PDOException $e) {
   error_log("Login Error: " . $e->getMessage());
  }
  return false;
 }

 public function logout()
 {
  session_start();
  session_unset();
  session_destroy();
  header("Location: login.php");
  exit();
 }
}

?>