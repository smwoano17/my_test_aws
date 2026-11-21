
<?php

require_once 'config/database.php';

$database = new Database();

$conn = $database->connect();

echo "Database connected successfully!";
