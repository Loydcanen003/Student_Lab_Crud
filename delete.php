<?php
include 'db_connect.php';

$id = $_GET['id'];

$sql = "DELETE FROM students WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    header("Location: select.php");
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
