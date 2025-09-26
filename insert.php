<?php
include 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'];
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $course     = $_POST['course'];
    $age        = $_POST['age'];
    $gender     = $_POST['gender'];
    $contact    = $_POST['contact'] ?? '';
    $address    = $_POST['address'] ?? '';

    try {
        $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, course, age, gender, contact, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisss", $student_id, $name, $email, $course, $age, $gender, $contact, $address);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ Student added successfully.</div>";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), "student_id") !== false) {
            $message = "<div class='alert alert-danger'>⚠️ Student ID already exists. Please use another one.</div>";
        } elseif (strpos($e->getMessage(), "email") !== false) {
            $message = "<div class='alert alert-danger'>⚠️ Email already exists. Please use another one.</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Student</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .card {
        border-radius: 20px;
        box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
    }
    .form-control, .form-select, textarea {
        border-radius: 12px;
    }
    .btn-primary {
        border-radius: 12px;
        font-weight: bold;
        padding: 10px 20px;
    }
    .btn-secondary {
        border-radius: 12px;
        padding: 10px 20px;
    }
</style>
</head>
<body>

<div class="card p-4 w-75">
    <h2 class="text-center text-primary mb-4">➕ Add New Student</h2>
    <?= $message ?? '' ?>
    <form method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Student ID</label>
                <input type="text" name="student_id" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Course</label>
                <input type="text" name="course" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Age</label>
                <input type="number" name="age" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select Gender</option>
                    <option value="Male">♂ Male</option>
                    <option value="Female">♀ Female</option>
                    <option value="Other">⚧ Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Contact</label>
                <input type="text" name="contact" class="form-control">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-semibold">Address</label>
                <textarea name="address" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="mt-4 d-flex justify-content-between">
            <a href="select.php" class="btn btn-secondary">⬅ Back</a>
            <button type="submit" class="btn btn-primary">✅ Add Student</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
