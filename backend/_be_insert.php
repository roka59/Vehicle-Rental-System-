<?php
include('../include/_authen_admin.php');
include('../connections/conn.php');

$table = $_GET['table'] ?? '';
$allowed_tables = ['books', 'categories', 'users', 'orders'];

if (!in_array($table, $allowed_tables)) {
    die("Invalid table selection");
}

// Get primary key column name
$result = $conn->query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
$primary_key = $result->fetch_assoc()['Column_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields = [];
    $values = [];
    $types = '';
    $params = [];
    
    // Get table structure
    $result = $conn->query("DESCRIBE $table");
    while ($row = $result->fetch_assoc()) {
        $field_name = $row['Field'];
        if ($field_name != $primary_key && isset($_POST[$field_name])) {
            $fields[] = $field_name;
            $values[] = '?';
            
            // Determine parameter type
            switch($row['Type']) {
                case (preg_match('/int/', $row['Type']) ? true : false):
                    $types .= 'i';
                    $params[] = (int)$_POST[$field_name];
                    break;
                case (preg_match('/decimal|float|double/', $row['Type']) ? true : false):
                    $types .= 'd';
                    $params[] = (float)$_POST[$field_name];
                    break;
                default:
                    $types .= 's';
                    $params[] = $_POST[$field_name];
            }
        }
    }
    
    $sql = "INSERT INTO $table (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
    $stmt = $conn->prepare($sql);
    
    // Bind parameters dynamically
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if ($stmt->execute()) {
        header("Location: ./_be_interface.php?table=$table");
        exit();
    } else {
        $error = "Error inserting record: " . $conn->error;
    }
}

// Get table structure for form
$result = $conn->query("DESCRIBE $table");
$fields = [];
while ($row = $result->fetch_assoc()) {
    if ($row['Field'] != $primary_key) {
        $fields[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Record - <?php echo ucfirst($table); ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="admin-form">
        <h1>Add New <?php echo ucfirst(substr($table, 0, -1)); ?></h1>
        
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="post" action="">
            <?php foreach($fields as $field): ?>
                <div class="form-group">
                    <label for="<?php echo $field['Field']; ?>">
                        <?php echo ucwords(str_replace('_', ' ', $field['Field'])); ?>:
                    </label>
                    
                    <?php if (strpos($field['Type'], 'text') !== false): ?>
                        <textarea id="<?php echo $field['Field']; ?>" 
                                name="<?php echo $field['Field']; ?>"
                                <?php echo $field['Null'] === 'NO' ? 'required' : ''; ?>></textarea>
                    
                    <?php elseif (strpos($field['Type'], 'enum') !== false): ?>
                        <select id="<?php echo $field['Field']; ?>"
                                name="<?php echo $field['Field']; ?>"
                                <?php echo $field['Null'] === 'NO' ? 'required' : ''; ?>>
                            <?php
                            preg_match('/enum\((.*)\)/', $field['Type'], $matches);
                            $enum_values = str_getcsv($matches[1], ',', "'");
                            foreach($enum_values as $value):
                                echo "<option value='$value'>$value</option>";
                            endforeach;
                            ?>
                        </select>
                    
                    <?php else: ?>
                        <input type="<?php 
                            if (strpos($field['Type'], 'int') !== false) echo 'number';
                            elseif (strpos($field['Type'], 'decimal') !== false) echo 'number" step="0.01';
                            elseif ($field['Field'] === 'email') echo 'email';
                            elseif ($field['Field'] === 'password') echo 'password';
                            else echo 'text';
                        ?>"
                        id="<?php echo $field['Field']; ?>"
                        name="<?php echo $field['Field']; ?>"
                        <?php echo $field['Null'] === 'NO' ? 'required' : ''; ?>>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <div class="form-actions">
                <button type="submit">Add Record</button>
                <a href="./_be_interface.php?table=<?php echo $table; ?>" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>