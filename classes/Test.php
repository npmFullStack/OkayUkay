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
}
?>