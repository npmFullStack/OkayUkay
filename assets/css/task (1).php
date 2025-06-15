<?php
session_start();
if (!isset($_SESSION["user_id"])) {
 header("Location: login.php");
 exit();
}

require_once "classes/Database.php";
require_once "classes/Task.php";

$database = new Database();
$db = $database->connect();

$task = new Task($db);
$tasks = $task->fetchTasks($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="assets/css/styles.css" rel="stylesheet">
        <link href="assets/css/task.css" rel="stylesheet">

</head>
<body>
    <?php require "components/sidebar.php"; ?>
    
    <div class="task-container">
      <div class="top-bar">
        <button class="burger">
          <i class="fas fa-bars"></i>
        </button>
        Tasks
      </div>

<div class="task-content">

  <div class="top-layer">
<div class="search-bar">
  <div class="search-bar-content">
    <i class="fas fa-magnifying-glass search-icon"></i>
    <input type="search" id="search" placeholder="Search..." autocomplete="off">
    <div class="category-dropdown">
      <button class="category-btn">
        <i class="fas fa-filter filter-icon"></i>
      </button>
      <div class="category-menu">
        <ul>
          <li><a href="#" class="filter-option" data-filter="all">All Tasks</a></li>
          <li><a href="#" class="filter-option" data-filter="upcoming">Upcoming</a></li>
          <li><a href="#" class="filter-option" data-filter="overdue">Overdue</a></li>
          <li><a href="#" class="filter-option" data-filter="done">Done</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>

<button class="btn-add-task">
      <a href="addtask.php">Add Task
        </a>
</button>
  </div>
  
  <div class="card-holder">
<?php foreach ($tasks as $task): ?>
    <div class="card">
<div class="card-head">
  <i class="fas fa-ellipsis-h dropdown-toggle"></i>
<div class="dropdown-menu">
  <ul>
    <li><a href="mark-done.php?id=<?= $task["id"] ?>">Mark as done</a></li>
    <li><a href="update.php?id=<?= $task["id"] ?>">Update</a></li>
    <li><a href="delete.php?id=<?= $task["id"] ?>">Delete</a></li>
  </ul>
</div>

</div>

<div class="card-body">
    <h4><?= $task["task_name"] ?></h4>
    <div class="form-group">
        <span class="due-date"> <p>Due Date:</p>
            <?= $task["due_date"]
             ? date("F j,Y h:i A", strtotime($task["due_date"]))
             : "No due date" ?> 
        </span>
        <?php if ($task["is_done"]): ?>
            <span class="done">Done</span>
        <?php elseif ($task["due_date"]): ?>
            <span class="<?= strtotime($task["due_date"]) > time()
             ? "upcoming"
             : "overdue" ?>">
              <?= strtotime($task["due_date"]) > time()
               ? "Upcoming"
               : "Overdue" ?>
            </span>
        <?php endif; ?>
    </div>
</div>
    </div>
<?php endforeach; ?>
</div>

  
</div>
    <!-- FLOATING BUTTON -->
    <div class="floating-add-btn">
      <a href="addtask.php">
        <i class="fas fa-plus-circle"></i>
      </a>
    </div>

    </div>
    
<script src="assets/js/script.js">
</script>
    
</body>
</html>