<?php

class Database {
  
  public function connect(){
  try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=okayukay", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e){
    die("Connection failed: " . $e->getMessage()); }
}
}