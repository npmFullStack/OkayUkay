<?php
session_start();
if (!isset($_SESSION["user_id"])) {
 header("Location: login.php");
 exit();
}

require_once "classes/Database.php";
require_once "classes/Task.php";

$db = new Database();
$task = new Task($db->connect());

// Fetch task statistics
$totalTasks = $task->countTotalTasks($_SESSION["user_id"]);
$overdueTasks = $task->countOverdueTasks($_SESSION["user_id"]);
$completedTasks = $task->countCompletedTasks($_SESSION["user_id"]);
$monthlyCompleted = $task->getMonthlyCompletedTasks($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php require "components/sidebar.php"; ?>
    
    <div class="dashboard-container">
        <div class="top-bar">
            <button class="burger">
                <i class="fas fa-bars"></i>
            </button>
            Dashboard
        </div>

        <div class="dashboard-content">
            <div class="welcome-section">
                <h4 class="welcome-message">Welcome Back, <?php echo htmlspecialchars(
                 $_SESSION["email"],
                ); ?>!</h4>
                <p class="join-date"><strong>Joined:</strong>
                    <?php if (isset($_SESSION["created_at"])) {
                     $timestamp = strtotime($_SESSION["created_at"]);
                     $date = date("F j, Y", $timestamp);
                     $time = date("h:i A", $timestamp);
                     echo $date . " at " . $time;
                    } else {
                     echo "N/A";
                    } ?>
                </p>
            </div>
            
            <div class="stats-section">
                <div class="card-holder">
                    <div class="card-item" id="totalTask">
                        <div>
                            <span>Total Tasks</span>
                            <h1><?php echo $totalTasks; ?></h1>
                        </div>
                        <div>
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                    
                    <div class="card-item" id="totalOverdueTask">
                        <div>
                            <span>Overdue Tasks</span>
                            <h1><?php echo $overdueTasks; ?></h1>
                        </div>
                        <div>
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    
                    <div class="card-item" id="completedTask">
                        <div>
                            <span>Completed Tasks</span>
                            <h1><?php echo $completedTasks; ?></h1>
                        </div>
                        <div>
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="chart-section">
                <div class="chart-container">
                    <canvas id="tasksChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart configuration
            const ctx = document.getElementById('tasksChart').getContext('2d');
            const tasksChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(
                     array_keys($monthlyCompleted),
                    ); ?>,
                    datasets: [{
                        label: 'Completed Tasks',
                        data: <?php echo json_encode(
                         array_values($monthlyCompleted),
                        ); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Completed Tasks',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>