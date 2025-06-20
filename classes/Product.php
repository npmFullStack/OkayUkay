<?php

require_once "Database.php";

class Product
{
  private $db;
  private $table = "products";

  public function __construct($db) {
    $this->db = $db;
  }

  public function fetchProducts($user_id) {
    try {
      $sql = "SELECT * FROM $this->table WHERE user_id = :user_id AND status = 1";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(":user_id", $user_id);
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      throw new Exception("Error fetchig products: " . $e->getMessage());
    }
  }

  public function store($name, $price, $image, $stock, $category_id, $user_id) {
    try {
      $sql = "INSERT INTO $this->table (name, price, image, stock, user_id, category_id) VALUES (:name, :price, :image, :stock, :user_id, :category_id)";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":price", $price);
      $stmt->bindParam(":image", $image);
      $stmt->bindParam(":stock", $stock);
      $stmt->bindParam(":user_id", $user_id);
      $stmt->bindParam(":category_id", $category_id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Error Adding Product" . $e->getMessage());
    }
  }

  public function updateTask($id, $name, $price, $image, $stock, $category_id, $user_id) {
    try {
      $sql = "UPDATE $this->table SET name = :name, price = :price, image = :image, stock = :stock WHERE id = :id AND user_id = :user_id AND category_id = :category_id";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(":name", $name);
      $stmt->bindParam(":price", $price);
      $stmt->bindParam(":image", $image);
      $stmt->bindParam(":stock", $stock);
      $stmt->bindParam(":id", $id);
      $stmt->bindParam(":category_id", $category_id);
      $stmt->bindParam(":user_id", $user_id);
      return $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Update Task Error: " . $e->getMessage());
    }
  }

  public function getAllProducts() {
    $sql = "SELECT p.*, c.name as category_name
            FROM $this->table p
            JOIN categories c ON p.category_id = c.id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function getAllCategories() {
    $sql = "SELECT * FROM categories ORDER BY name";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getProductsByCategory($category_id) {
    $sql = "SELECT * FROM $this->table WHERE category_id = :category_id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(":category_id", $category_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  public function getProductById($id, $user_id) {
    try {
      $sql = "SELECT * FROM $this->table WHERE id = :id AND user_id = :user_id LIMIT 1";
      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(":id", $id);
      $stmt->bindParam(":user_id", $user_id);
      $stmt->execute();
      return $stmt->fetch();
    } catch (PDOException $e) {
      throw new Exception("Get Task Error: " . $e->getMessage());
    }
  }
}