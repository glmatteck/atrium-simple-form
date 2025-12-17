<?php
// Development environment
$dsn = 'sqlite:' . __DIR__ . '/../src/Database/data.db';
$user = null;
$pass = null;

// "Production" environment (assume MySQL)
// $host = '##HOST##';
// $db   = '##DBNAME##';
// $user = '##USER##';
// $pass = '##PASS##';
// $dsn = "mysql:host=$host;dbname=$db";

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];

return [
    'dsn' => $dsn,
    'user' => $user,
    'pass' => $pass,
    'options' => $options
];