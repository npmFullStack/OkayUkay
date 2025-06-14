<?php
require_once "classes/Database.php";
require_once "classes/User.php";
$database = new Database();
$db = $database->connect();
$user = new User($db);
$user->logout();

?>
