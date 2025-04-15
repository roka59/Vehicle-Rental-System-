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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields = [];
    $types = '';
    $params = [];
    
    // Get table structure
    $result = $conn->query("DESCRIBE $table");
    while ($row = $result->fetch_assoc()) {
        $field_name = $row['Field'];
        if ($field_name != $primary_key && isset($_POST[$field_name])) {
            $fields[] = "$field_name = ?";
            
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
    
    // Add ID to parameters
    $types .= 'i';
    $params[] = $id;
    
    $sql = "UPDATE $table SET " . implode(',', $fields) . " WHERE $primary_key = ?";
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if ($stmt->execute()) {
        header("Location: ./_be_interface.php?table=$table");
        exit();
    } else {
        $error = "Error updating record: " . $conn->error;
    }
}

// Get current record
$stmt = $conn->prepare("SELECT * FROM $table WHERE $primary_key = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$record = $stmt->get_result()->fetch_assoc();

if (!$record) {
    die("Record not found");
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
    <title>Edit Record - <?php echo ucfirst($table); ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="admin-form">
        <h1>Edit <?php echo ucfirst(substr($table, 0, -1)); ?></h1>
        
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
                                <?php echo $field['Null'] === 'NO' ? 'required' : ''; ?>><?php echo htmlspecialchars($record[$field['Field']]); ?></textarea>
                    
                    <?php elseif (strpos($field['Type'], 'enum') !== false): ?>
                        <select id="<?php echo $field['Field']; ?>"
                                name="<?php echo $field['Field']; ?>"
                                <?php echo $field['Null'] === 'NO' ? 'required' : ''; ?>>
                            <?php
                            preg_match('/enum\((.*)\)/', $field['Type'], $matches);
                            $enum_values = str_getcsv($matches[1], ',', "'");
                            foreach($enum_values as $value):
                                $selected = ($record[$field['Field']] == $value) ? 'selected' : '';
                                echo "<option value='$value' $selected>$value</option>";
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
                        value="<?php echo htmlspecialchars($record[$field['Field']]); ?>"
                        <?php echo $field['Null'] === 'NO' ? 'required' : ''; ?>>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <div class="form-actions">
                <button type="submit">Update Record</button>
                <a href="./_be_interface.php?table=<?php echo $table; ?>" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>