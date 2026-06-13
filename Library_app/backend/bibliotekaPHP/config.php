<?php
// Database configuration helper
function getDbConnection() {
    $host = getenv('DB_HOST') ?: 'mysql';
    $dbname = getenv('DB_NAME') ?: 'library';
    $username = getenv('DB_USER') ?: 'library_user';
    $password = getenv('DB_PASS') ?: 'library_pass';
    
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
        exit();
    }
}

function getMysqliConnection() {
    $host = getenv('DB_HOST') ?: 'mysql';
    $dbname = getenv('DB_NAME') ?: 'library';
    $username = getenv('DB_USER') ?: 'library_user';
    $password = getenv('DB_PASS') ?: 'library_pass';
    
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Błąd połączenia z bazą danych']);
        exit();
    }
    return $conn;
}
?>
