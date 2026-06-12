<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $pdo = getDbConnection();

    $book_id = intval($_GET['book_id'] ?? 0);
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    $stmt = $pdo->prepare("SELECT rating FROM book_ratings WHERE book_id = :book_id AND user_ip = :user_ip LIMIT 1");
    
    if ($stmt === false) {
        http_response_code(500);
        $errorInfo = $pdo->errorInfo();
        echo json_encode(['user_rating' => 0, 'error' => 'Błąd przygotowania zapytania SQL: ' . ($errorInfo[2] ?? 'Brak szczegółów')]);
        exit;
    }
    
    $stmt->execute(['book_id' => $book_id, 'user_ip' => $user_ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['user_rating' => $result ? $result['rating'] : 0, 'user_ip' => $user_ip]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['user_rating' => 0, 'error_pdo' => 'Błąd połączenia z bazą: ' . $e->getMessage()]);
}
?>
