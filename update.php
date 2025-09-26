<?php 
include 'db_connect.php';

if (!isset($_GET['id'])) {
    die("<div class='alert alert-danger mt-3'>Error: No student ID provided.</div>");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<div class='alert alert-danger mt-3'>Error: Student not found.</div>");
}

$row = $result->fetch_assoc();
$stmt->close();

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'] ?? '';
    $name       = $_POST['name'] ?? '';
    $email      = $_POST['email'] ?? '';
    $course     = $_POST['course'] ?? '';
    $age        = $_POST['age'] ?? '';
    $gender     = $_POST['gender'] ?? '';
    $contact    = $_POST['contact'] ?? '';
    $address    = $_POST['address'] ?? '';

    $update_stmt = $conn->prepare("UPDATE students 
        SET student_id=?, name=?, email=?, course=?, age=?, gender=?, contact=?, address=? 
        WHERE id=?");
    $update_stmt->bind_param("ssssisssi", $student_id, $name, $email, $course, $age, $gender, $contact, $address, $id);

    if ($update_stmt->execute()) {
        $message = "<div class='alert alert-success mt-3'>✅ Student record updated successfully.</div>";
    } else {
        if ($conn->errno == 1062) {
            $message = "<div class='alert alert-danger mt-3'>⚠️ Duplicate entry! Student ID or Email already exists.</div>";
        } else {
            $message = "<div class='alert alert-danger mt-3'>❌ Error: " . $update_stmt->error . "</div>";
        }
    }

    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #e3f2fd, #f9f9f9);
      min-height: 100vh;
    }

    .container-box {
      max-width: 800px;
      margin: 60px auto;
      background: #fff;
      border-radius: 20px;
      padding: 30px 40px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .title {
      font-weight: 700;
      font-size: 26px;
      color: #0d47a1;
      margin-bottom: 20px;
      text-align: center;
    }

    label {
      font-weight: 600;
      color: #333;
    }

    .form-control, .form-select, textarea {
      border-radius: 12px;
      box-shadow: none;
      border: 1px solid #ccc;
    }
    .form-control:focus, .form-select:focus, textarea:focus {
      border-color: #1e88e5;
      box-shadow: 0 0 5px rgba(30,136,229,0.4);
    }

    .btn-custom {
      border-radius: 10px;
      font-weight: 600;
      padding: 10px 20px;
      transition: all 0.3s ease;
    }
    .btn-update {
      background: linear-gradient(45deg, #1e88e5, #42a5f5);
      color: #fff;
      border: none;
    }
    .btn-update:hover {
      background: linear-gradient(45deg, #1565c0, #1e88e5);
    }
    .btn-back {
      background: #e0e0e0;
      color: #333;
    }
    .btn-back:hover {
      background: #bdbdbd;
    }
  </style>
</head>
<body>

<div class="container-box">
  <h2 class="title">✏️ Edit Student</h2>
  <?= $message ?>

  <form action="" method="POST">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label>Student ID <span class="text-danger">*</span></label>
        <input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($row['student_id'] ?? '') ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label>Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] ?? '') ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label>Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email'] ?? '') ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label>Course <span class="text-danger">*</span></label>
        <input type="text" name="course" class="form-control" value="<?= htmlspecialchars($row['course'] ?? '') ?>" required>
      </div>
      <div class="col-md-4 mb-3">
        <label>Age <span class="text-danger">*</span></label>
        <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($row['age'] ?? '') ?>" required>
      </div>
      <div class="col-md-4 mb-3">
        <label>Gender <span class="text-danger">*</span></label>
        <select name="gender" class="form-select" required>
          <option value="">Select Gender</option>
          <option value="Male" <?= ($row['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= ($row['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Other" <?= ($row['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
      </div>
      <div class="col-md-4 mb-3">
        <label>Contact</label>
        <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($row['contact'] ?? '') ?>">
      </div>
      <div class="col-md-12 mb-3">
        <label>Address</label>
        <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($row['address'] ?? '') ?></textarea>
      </div>
    </div>

    <div class="d-flex justify-content-between">
      <a href="select.php" class="btn btn-back btn-custom">⬅ Back</a>
      <button type="submit" class="btn btn-update btn-custom">💾 Update</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
