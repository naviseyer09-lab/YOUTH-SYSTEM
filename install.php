<?php
set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

$sqlFile = __DIR__ . '/setup.sql';
if(!file_exists($sqlFile)){
    die("setup.sql not found.");
}

$mysqli = new mysqli('localhost', 'root', '');
if($mysqli->connect_error){
    die('Connect error: ' . $mysqli->connect_error);
}

$sql = file_get_contents($sqlFile);
$statements = explode(';', $sql);

foreach($statements as $statement) {
    $statement = trim($statement);
    if(empty($statement)) {
        continue;
    }
    
    if($mysqli->query($statement)) {
        continue;
    } else {
        echo "ERROR: " . $mysqli->error . "<br>";
    }
}

echo "Database installed/updated successfully!<br>";

$mysqli->select_db('youth_skills_system');

// Fix trainings table - ensure it has all needed columns
$columns = $mysqli->query("SHOW COLUMNS FROM trainings");
$col_names = array();
while($col = $columns->fetch_assoc()) {
    $col_names[] = $col['Field'];
}

// Add missing columns
if(!in_array('company_id', $col_names)) {
    $mysqli->query("ALTER TABLE trainings ADD COLUMN company_id INT AFTER id");
    echo "Added company_id column.<br>";
}

if(!in_array('created_at', $col_names)) {
    $mysqli->query("ALTER TABLE trainings ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    echo "Added created_at column.<br>";
}

$hash = password_hash('password', PASSWORD_DEFAULT);
$escaped_hash = $mysqli->real_escape_string($hash);
$mysqli->query("UPDATE users SET password = '" . $escaped_hash . "' WHERE id = 1");
echo "Password updated.<br>";

// Ensure all trainings have company_id
$mysqli->query("UPDATE trainings SET company_id = 1 WHERE company_id IS NULL");

$mysqli->close();
echo "Done!";
?>
