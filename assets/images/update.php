<?php
session_start();
require_once "classes/Database.php";
require_once "classes/Task.php";

if (!isset($_SESSION["user_id"])) {
 header("Location: login.php");
 exit();
}

$database = new Database();
$db = $database->connect();
$task = new Task($db);

$message = "";
$status = "";

$taskId = $_GET["id"] ?? ($_POST["task_id"] ?? null);

if (!$taskId) {
 header("Location: task.php");
 exit();
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
 $name = $_POST["name"] ?? "";
 $due_date = $_POST["date"] ?? "";

 if (!empty($name) && !empty($due_date)) {
  $success = $task->updateTask($taskId, $name, $due_date, $_SESSION["user_id"]);
  $status = $success ? "success" : "error";
  $message = $success ? "Task updated successfully" : "Update failed";
 } else {
  $status = "error";
  $message = "All fields required.";
 }
}

$existingTask = $task->getTaskById($taskId, $_SESSION["user_id"]);
if (!$existingTask) {
 header("Location: task.php");
 exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoTrack | Add Task</title>
    
    <!-- J-QUERY -->
      <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
      
          <!-- TOASTR -->
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
          <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- STYLES -->
    <link href="assets/css/styles.css" rel="stylesheet">
        <link href="assets/css/addtask.css" rel="stylesheet">

</head>
<body>
    <?php require "components/sidebar.php"; ?>
    
    <div class="add-task-container">
      <div class="top-bar">
        <button class="burger">
          <i class="fas fa-bars"></i>
        </button>
        Add Task
      </div>

<div class="add-task-content">
  <div class="add-task-wrapper">
    <div class="image-section">
      <img src="assets/images/updatetask.png" alt="Update Task Image"/>
    </div>
    <div class="form-section">
      <h1>Update your task
      </h1>
      <p>Fill in the task details below.
      </p>
      <form action="update.php" method="POST">
        <div class="form-group">
          <label for="name">
            Task Name
          </label>
          <input type="text" id="name" name="name" value="<?= $existingTask[
           "task_name"
          ] ?>">
          </input>
        </div>
        
        <div class="form-group">
          <label for="task-duedate">Due Date
          </label>
          <input type="datetime-local" id="date" name="date" value="<?= $existingTask[
           "due_date"
          ] ?>">
          </input>
        </div>
        <input type="hidden" name="task_id" value="<?= $taskId ?>">

        <button type="submit" class="btn-add">Update
        </button>
      </form>
    </div>
  </div>
</div>
<?php if (!empty($message)): ?>
<script>
  toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
  };
  toastr. <?= $status ?>("<?= $message ?>");
  
  <?php if ($status == "success"): ?>
  setTimeout(function(){
    window.location.href = "task.php";
  }, 1000);
  <?php endif; ?>
    </script>
    <?php endif; ?>
  
  
</script>

    </div>
    
</body>
</html>