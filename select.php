<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #e3f2fd, #f9f9f9);
      color: #333;
      min-height: 100vh;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }
    .header h2 {
      font-weight: 700;
      color: #0d47a1;
    }

    /* Card Style */
    .card {
      border-radius: 20px;
      border: none;
      background: #ffffff;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }

    /* Table */
    .table {
      border-collapse: separate;
      border-spacing: 0 10px;
    }
    .table thead {
      background: #0d47a1;
      color: #fff;
      border-radius: 12px;
    }
    .table thead th {
      border: none;
      padding: 15px;
      font-size: 15px;
    }
    .table tbody tr {
      background: #f5f9ff;
      border-radius: 12px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.05);
    }
    .table tbody td {
      padding: 15px;
      vertical-align: middle;
    }
    .table tbody tr:hover {
      background: #e8f0fe;
      transform: scale(1.01);
      transition: 0.2s;
    }

    /* Buttons */
    .btn-custom {
      border-radius: 10px;
      padding: 6px 14px;
      font-size: 14px;
      font-weight: 600;
      border: none;
      transition: all 0.2s;
    }
    .btn-add {
      background: linear-gradient(45deg, #1e88e5, #42a5f5);
      color: #fff;
    }
    .btn-add:hover {
      background: linear-gradient(45deg, #1565c0, #1e88e5);
    }
    .btn-edit {
      background: #ffca28;
      color: #000;
    }
    .btn-edit:hover {
      background: #f4b400;
    }
    .btn-delete {
      background: #e53935;
      color: #fff;
    }
    .btn-delete:hover {
      background: #c62828;
    }

    /* Modal */
    .modal-content {
      border-radius: 15px;
      border: none;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .modal-header {
      border-bottom: none;
      border-radius: 15px 15px 0 0;
    }
  </style>
</head>
<body>

<div class="container py-5">

  <!-- Header -->
  <div class="header">
    <h2>📚 Student Records</h2>
    <a href="insert.php" class="btn btn-add btn-custom shadow-sm">
      ➕ Add Student
    </a>
  </div>

  <!-- Card -->
  <div class="card p-4">
    <table class="table align-middle text-center">
      <thead>
        <tr>
          <th>#</th>
          <th>👤 Name</th>
          <th>📧 Email</th>
          <th>🎓 Course</th>
          <th>⚙️ Action</th>
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
                <td class='fw-semibold'>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['course']}</td>
                <td>
                  <a href='update.php?id={$row['id']}' class='btn btn-edit btn-custom me-1'>✏️ Edit</a>
                  <button class='btn btn-delete btn-custom' data-bs-toggle='modal' data-bs-target='#deleteModal{$row['id']}'>🗑️ Delete</button>
                </td>
              </tr>

              <!-- Delete Modal -->
              <div class='modal fade' id='deleteModal{$row['id']}' tabindex='-1'>
                <div class='modal-dialog modal-dialog-centered'>
                  <div class='modal-content'>
                    <div class='modal-header bg-danger text-white'>
                      <h5 class='modal-title'>⚠️ Confirm Delete</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>
                    <div class='modal-body text-center'>
                      <p>Are you sure you want to delete <strong>{$row['name']}</strong>?</p>
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-secondary btn-custom' data-bs-dismiss='modal'>Cancel</button>
                      <a href='delete.php?id={$row['id']}' class='btn btn-delete btn-custom'>Yes, Delete</a>
                    </div>
                  </div>
                </div>
              </div>
            ";
          }
        } else {
          echo "<tr><td colspan='5' class='text-muted py-4'>🚫 No student records found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
