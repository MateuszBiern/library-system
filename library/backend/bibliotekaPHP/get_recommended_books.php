<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $pdo = getDbConnection();

    // Pobierz książki z średnim ratingiem > 3.0
    $stmt = $pdo->prepare("
        SELECT b.id, b.title, b.cover_image, b.description, ROUND(AVG(r.rating),1) as average_rating
        FROM books b
        JOIN book_ratings r ON b.id = r.book_id
        GROUP BY b.id
        HAVING average_rating >= 3.0
    ");
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($books);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
