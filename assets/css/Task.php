<?php
require_once "Database.php";
class Task
{
 private $db;
 private $table = "tasks";

 public function __construct($db)
 {
  $this->db = $db;
 }

 public function fetchTasks($user_id)
{
    try {
        $sql = "SELECT *, 
                CASE 
                    WHEN is_done = 1 THEN 'Done'
                    WHEN due_date IS NULL THEN NULL
                    WHEN due_date < NOW() THEN 'Overdue'
                    ELSE 'Upcoming'
                END as status
                FROM $this->table 
                WHERE user_id = :user_id AND is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        throw new Exception("Fetching Task Error: " . $e->getMessage());
    }
}

 public function store($name, $due_date, $user_id)
 {
  try {
   $sql =
    "INSERT INTO tasks (task_name, due_date, user_id) VALUES (:task_name, :due_date, :user_id)";
   $stmt = $this->db->prepare($sql);
   $stmt->bindParam(":task_name", $name);
   $stmt->bindParam(":due_date", $due_date);
   $stmt->bindParam(":user_id", $user_id);
   return $stmt->execute();
  } catch (PDOException $e) {
   throw new Exception("Adding Task Error: " . $e->getMessage());
  }
 }

 public function markDone($id, $user_id)
 {
  try {
   $sql = "UPDATE $this->table SET is_done = 1 WHERE id = :id AND user_id = :user_id";
   $stmt = $this->db->prepare($sql);
   $stmt->bindParam(":id", $id);
   $stmt->bindParam(":user_id", $user_id);
   return $stmt->execute();
  } catch (PDOException $e) {
   throw new Exception("Mark Done Error: " . $e->getMessage());
  }
 }

 public function deleteTask($id, $user_id)
 {
  try {
   $sql = "UPDATE $this->table SET is_active = 0 WHERE id = :id AND user_id = :user_id";
   $stmt = $this->db->prepare($sql);
   $stmt->bindParam(":id", $id);
   $stmt->bindParam(":user_id", $user_id);
   return $stmt->execute();
  } catch (PDOException $e) {
   throw new Exception("Delete Task Error: " . $e->getMessage());
  }
 }

 public function updateTask($id, $name, $due_date, $user_id)
 {
  try {
   $sql = "UPDATE $this->table SET task_name = :task_name, due_date = :due_date WHERE id = :id AND user_id = :user_id";
   $stmt = $this->db->prepare($sql);
   $stmt->bindParam(":task_name", $name);
   $stmt->bindParam(":due_date", $due_date);
   $stmt->bindParam(":id", $id);
   $stmt->bindParam(":user_id", $user_id);
   return $stmt->execute();
  } catch (PDOException $e) {
   throw new Exception("Update Task Error: " . $e->getMessage());
  }
 }

 public function getTaskById($id, $user_id)
 {
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
 
 public function countTotalTasks($user_id)
{
    try {
        $sql = "SELECT COUNT(*) as count FROM $this->table WHERE user_id = :user_id AND is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    } catch (PDOException $e) {
        throw new Exception("Count Task Error: " . $e->getMessage());
    }
}

public function countOverdueTasks($user_id)
{
    try {
        $currentDate = date('Y-m-d H:i:s');
        $sql = "SELECT COUNT(*) as count FROM tasks
                WHERE user_id = :user_id 
                AND is_active = 1 
                AND is_done = 0 
                AND due_date < :currentDate";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":currentDate", $currentDate);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    } catch (PDOException $e) {
        throw new Exception("Count Overdue Task Error: " . $e->getMessage());
    }
}

public function countCompletedTasks($user_id)
{
    try {
        $sql = "SELECT COUNT(*) as count FROM tasks
                WHERE user_id = :user_id 
                AND is_active = 1 
                AND is_done = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    } catch (PDOException $e) {
        throw new Exception("Count Completed Task Error: " . $e->getMessage());
    }
}

public function getMonthlyCompletedTasks($user_id)
{
    try {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month, 
                    COUNT(*) as count 
                FROM tasks
                WHERE user_id = :user_id 
                AND is_active = 1 
                AND is_done = 1 
                GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                ORDER BY month ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        $monthlyData = [];
        
        foreach ($results as $row) {
            $monthlyData[$row['month']] = (int)$row['count'];
        }
        
        // Fill in missing months with 0
        $currentDate = new DateTime();
        $startDate = new DateTime('first day of this month -11 months');
        $interval = new DateInterval('P1M');
        $period = new DatePeriod($startDate, $interval, $currentDate);
        
        $completeData = [];
        foreach ($period as $date) {
            $monthKey = $date->format('Y-m');
            $completeData[$monthKey] = $monthlyData[$monthKey] ?? 0;
        }
        
        return $completeData;
    } catch (PDOException $e) {
        throw new Exception("Monthly Completed Task Error: " . $e->getMessage());
    }
}
}
