<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Task Manager</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Task Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="create.php">Add New Task</a> <!-- Link to create.php -->
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container my-4">
      <table class="table mt-4">
        <thead>
          <tr>
            <th>ID</th>
            <th>TASK NAME</th>
            <th>STATUS</th>
            <th>DUE DATE</th>
            <th>ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php
            include 'db.php'; 
            $sql = "SELECT * FROM tasks";
            $result = $conn->query($sql);

            if(!$result){
                die("Invalid query!" . $conn->error);
            }

            while ($row=$result->fetch_assoc()) {
                echo "
                <tr>
                    <td>{$row['id']}</td>
                    <td>{$row['task_name']}</td> 
                    <td>{$row['status']}</td>
                    <td>{$row['due_date']}</td>
                    <td>
                        <a class='btn btn-success' href='update.php?id={$row['id']}'>Edit</a>
                        <a class='btn btn-danger' href='delete.php?id={$row['id']}'>Delete</a>
                    </td>
                </tr>
                ";
            }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>