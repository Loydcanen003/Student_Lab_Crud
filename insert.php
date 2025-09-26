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
</head>
<body class="container mt-5">

<h2>Add New Student</h2>
<?= $message ?? '' ?>
<form method="POST" class="w-75">
    <div class="row">
        <div class="col-md-6 mb-3"><label>Student ID</label><input type="text" name="student_id" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label>Course</label><input type="text" name="course" class="form-control" required></div>
        <div class="col-md-4 mb-3"><label>Age</label><input type="number" name="age" class="form-control" required></div>
        <div class="col-md-4 mb-3"><label>Gender</label>
            <select name="gender" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="col-md-4 mb-3"><label>Contact</label><input type="text" name="contact" class="form-control"></div>
        <div class="col-md-12 mb-3"><label>Address</label><textarea name="address" class="form-control" rows="3"></textarea></div>
    </div>
    <button type="submit" class="btn btn-primary">Add Student</button>
    <a href="select.php" class="btn btn-secondary">Back</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
