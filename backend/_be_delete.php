<?php
include('../include/_authen_admin.php');
include('../connections/conn.php');

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? 0;
$allowed_tables = ['books', 'categories', 'users', 'orders'];

if (!in_array($table, $allowed_tables)) {
    die("Invalid table selection");
}

// Get primary key column name
$result = $conn->query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
$primary_key = $result->fetch_assoc()['Column_name'];

// Prepare and execute delete statement
$stmt = $conn->prepare("DELETE FROM $table WHERE $primary_key = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ./_be_interface.php?table=$table");
    exit();
} else {
    die("Error deleting record: " . $conn->error);
}

$conn->close();
?>