<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $pdo = getDbConnection();
    $stmt = $pdo->query('SELECT * FROM tags ORDER BY name');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($results);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
