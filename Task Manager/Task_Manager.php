<?php
// Start the session at the beginning of the script
session_start();

include '../db.php'; // Include database connection
include '../Task Manager/TaskManager.php'; // Include the TaskManager class
include '../Sidebar/sidebar.php';

// Initialize the role variable from the session
if (!isset($_SESSION['role'])) {
    // Redirect to login if role is not set
    header("Location: ../login/login.php");
    exit();
}

$role = $_SESSION['role']; // Initialize the role variable

$taskManager = new TaskManager($conn); // Create an instance of TaskManager
$sidebar = new Sidebar($role); // Create Sidebar instance with the initialized role

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get values from the form
    $task_name = $_POST['task_name'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    // Create a new task
    $taskManager->createTask($task_name, $status, $due_date);
}

// Fetch all tasks from the database
$tasks = $taskManager->fetchAllTasks(); // Use the fetchAllTasks method

$sidebar->render();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../Design/taskmanager.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Add New Task</title>
</head>
<body>

<div class="container my-4">
    <h2>Create a New Task</h2>
    <form action="Task_Manager.php" method="POST">
        <div class="mb-3">
            <label for="task_name" class="form-label">Task Name</label>
            <input type="text" class="form-control" id="task_name" name="task_name" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="text" class="form-control" id="status" name="status" required>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h2 class="mt-4">Tasks List</h2>
    <table>
            <tr>
                <th>ID</th>
                <th>Task Name</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Action</th>
            </tr>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?php echo htmlspecialchars($task['id']); ?></td>
                    <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                    <td><?php echo htmlspecialchars($task['status']); ?></td>
                    <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
