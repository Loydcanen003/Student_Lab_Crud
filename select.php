<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">📚 Student Records</h2>
    <a href="insert.php" class="btn btn-success">
      ➕ Add Student
    </a>
  </div>

  <div class="card shadow">
    <div class="card-body">
      <table class="table table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM students ORDER BY id DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "
              <tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['course']}</td>
                <td>
                  <a href='update.php?id={$row['id']}' class='btn btn-sm btn-warning me-1'>✏️ Edit</a>
                  <button class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal{$row['id']}'>🗑️ Delete</button>
                </td>
              </tr>

              <!-- Delete Confirmation Modal -->
              <div class='modal fade' id='deleteModal{$row['id']}' tabindex='-1'>
                <div class='modal-dialog'>
                  <div class='modal-content'>
                    <div class='modal-header bg-danger text-white'>
                      <h5 class='modal-title'>Confirm Delete</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body'>
                      Are you sure you want to delete <strong>{$row['name']}</strong>?
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                      <a href='delete.php?id={$row['id']}' class='btn btn-danger'>Yes, Delete</a>
                    </div>
                  </div>
                </div>
              </div>
            ";
          }
        } else {
          echo "<tr><td colspan='5' class='text-center text-muted'>No records found</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
