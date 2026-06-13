<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $pdo = getDbConnection();

    if (!isset($_GET['book_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Brak parametru book_id']);
        exit;
    }

    $book_id = intval($_GET['book_id']);
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    // Średnia ocena i liczba głosów
    $stmt = $pdo->prepare("
        SELECT 
            IFNULL(ROUND(AVG(rating), 1), 0) AS average_rating,
            COUNT(*) AS total_ratings
        FROM book_ratings
        WHERE book_id = :book_id
    ");
    $stmt->execute(['book_id' => $book_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ocena dla tego IP
    $user_rating = 0;
    $stmt = $pdo->prepare("SELECT rating FROM book_ratings WHERE book_id = :book_id AND user_ip = :user_ip");
    $stmt->execute(['book_id' => $book_id, 'user_ip' => $user_ip]);
    $ur = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($ur) $user_rating = intval($ur['rating']);

    echo json_encode([
        'average_rating' => floatval($result['average_rating']),
        'total_ratings' => intval($result['total_ratings']),
        'user_rating' => $user_rating,
        'user_ip' => $user_ip
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
