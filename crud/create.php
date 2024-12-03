<?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Include database connection
          include '../db.php';

          // Get values from the form
          $task_name = $_POST['task_name'];
          $status = $_POST['status'];
          $due_date = $_POST['due_date'];

          // Insert the data into the database
          $sql = "INSERT INTO task (task_name, status, due_date) VALUES (?, ?, ?)";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("sss", $task_name, $status, $due_date);
          $stmt->execute();

          // Redirect to index.php after inserting the data
          header('Location: index.php');
          exit;
        }
      ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Add New Task</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Task Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="create.php">Add New Task</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container my-4">
      <h2>Create a New Task</h2>
      <form action="create.php" method="POST">
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

  </body>
</html>
