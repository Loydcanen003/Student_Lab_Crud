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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'] ?? '';
    $name       = $_POST['name'] ?? '';
    $email      = $_POST['email'] ?? '';
    $course     = $_POST['course'] ?? '';
    $age        = $_POST['age'] ?? '';
    $gender     = $_POST['gender'] ?? '';
    $contact      = $_POST['contact'] ?? '';
    $address    = $_POST['address'] ?? '';

    $update_stmt = $conn->prepare("UPDATE students 
        SET student_id=?, name=?, email=?, course=?, age=?, gender=?, contact=?, address=? 
        WHERE id=?");
    $update_stmt->bind_param("ssssisssi", $student_id, $name, $email, $course, $age, $gender, $contact, $address, $id);

    if ($update_stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>✅ Student record updated successfully.</div>";
    } else {
        if ($conn->errno == 1062) {
            echo "<div class='alert alert-danger mt-3'>⚠️ Duplicate entry! Student ID or Email already exists.</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>❌ Error: " . $update_stmt->error . "</div>";
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
</head>
<body class="container mt-5">

<h2>Edit Student</h2>

<form action="" method="POST" class="w-75">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Student ID <span class="text-danger">*</span></label>
            <input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($row['student_id'] ?? '') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] ?? '') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email'] ?? '') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Course <span class="text-danger">*</span></label>
            <input type="text" name="course" class="form-control" value="<?= htmlspecialchars($row['course'] ?? '') ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Age <span class="text-danger">*</span></label>
            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($row['age'] ?? '') ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Gender <span class="text-danger">*</span></label>
            <select name="gender" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="Male" <?= ($row['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($row['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= ($row['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Contact</label>
            <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($row['contact'] ?? '') ?>">
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($row['address'] ?? '') ?></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="select.php" class="btn btn-secondary">Back</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
