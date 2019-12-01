<?php
$dbServerName = 'localhost';
$dbUsername = '';
$dbPassword = '';
$dbName = 'php_mysql_ovn';
$charset = 'utf8mb4';
$collate = 'utf8mb4_unicode_ci';

try {
    $conn = new PDO("mysql:host=$dbServerName;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
