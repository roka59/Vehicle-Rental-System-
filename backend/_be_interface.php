<?php
include('../include/_authen_admin.php');
include('../connections/conn.php');

// Get table name from URL parameter
$allowed_tables = ['books', 'categories', 'users', 'orders'];
$table = isset($_GET['table']) ? $_GET['table'] : 'books'; // Default to books table

if (!in_array($table, $allowed_tables)) {
    $table = 'books'; // Fallback to books if invalid table selected
}

// Get all records from selected table using prepared statement
$stmt = $conn->prepare("SELECT * FROM " . $table);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Interface - BookNook</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include('../include/header_nav.php'); ?>

    <main>
        <div class="admin-interface">
            <h1>Admin Interface - <?php echo htmlspecialchars(ucfirst($table)); ?></h1>
            
            <nav class="admin-nav">
                <?php foreach($allowed_tables as $t): ?>
                    <a href="?table=<?php echo htmlspecialchars($t); ?>" 
                       class="<?php echo ($table === $t) ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars(ucfirst($t)); ?>
                    </a>
                <?php endforeach; ?>
                <a href="./_logoff.php">Logout</a>
            </nav>

            <div class="admin-actions">
                <a href="./_be_insert.php?table=<?php echo htmlspecialchars($table); ?>" class="btn btn-primary">Add New</a>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <?php
                        if ($result->num_rows > 0) {
                            $fields = $result->fetch_fields();
                            foreach ($fields as $field) {
                                echo "<th>" . htmlspecialchars($field->name) . "</th>";
                            }
                            echo "<th>Actions</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach($row as $key => $value) {
                                if ($key === 'password') {
                                    echo "<td>********</td>"; // Hide passwords
                                } elseif ($key === 'description') {
                                    echo "<td>" . htmlspecialchars(substr($value, 0, 100)) . "...</td>"; // Truncate long descriptions
                                } else {
                                    echo "<td>" . htmlspecialchars($value) . "</td>";
                                }
                            }
                            $id_field = key($row); // Get primary key field name
                            $id_value = $row[$id_field]; // Get primary key value
                            echo "<td class='actions'>";
                            echo "<a href='./_be_update.php?table=" . htmlspecialchars($table) . "&id=" . (int)$id_value . "' class='edit'>Edit</a> ";
                            echo "<a href='./_be_delete.php?table=" . htmlspecialchars($table) . "&id=" . (int)$id_value . "'
                                    class='delete'
                                    onclick='return confirm(\"Are you sure?\");'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='100%'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include('../include/footer.php'); ?>
</body>
</html>

<?php $conn->close(); ?>