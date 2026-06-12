<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

try {
    $pdo = getDbConnection();

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['book_id'], $data['rating'], $data['user_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Brak parametrów book_id, rating lub user_id']);
        exit;
    }

    $book_id = intval($data['book_id']);
    $rating = floatval($data['rating']);
    $user_id = intval($data['user_id']);
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    if ($rating < 1 || $rating > 5) {
        http_response_code(400);
        echo json_encode(['error' => 'Rating musi być 1-5']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM book_ratings WHERE book_id = :book_id AND user_id = :user_id");
    $stmt->execute(['book_id' => $book_id, 'user_id' => $user_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE book_ratings SET rating = :rating, updated_at = NOW() WHERE id = :id");
        $stmt->execute(['rating' => $rating, 'id' => $existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO book_ratings (book_id, user_id, user_ip, rating, created_at, updated_at) VALUES (:book_id, :user_id, :user_ip, :rating, NOW(), NOW())");
        $stmt->execute(['book_id' => $book_id, 'user_id' => $user_id, 'user_ip' => $user_ip, 'rating' => $rating]);
    }

    $stmt = $pdo->prepare("SELECT ROUND(AVG(rating), 1) AS average_rating, COUNT(*) AS total_ratings FROM book_ratings WHERE book_id = :book_id");
    $stmt->execute(['book_id' => $book_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'average_rating' => $result['average_rating'],
        'total_ratings' => $result['total_ratings'],
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>