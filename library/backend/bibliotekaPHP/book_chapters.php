<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

$bookId = $_GET['bookId'] ?? null;
$chapterId = $_GET['id'] ?? null;

try {
    $pdo = getDbConnection();

    if ($bookId) {
        // Pobierz wszystkie rozdziały książki
        $stmt = $pdo->prepare('SELECT * FROM book_chapters WHERE book_id = ? ORDER BY chapter_order');
        $stmt->execute([$bookId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } elseif ($chapterId) {
        // Pobierz pojedynczy rozdział
        $stmt = $pdo->prepare('SELECT * FROM book_chapters WHERE id = ?');
        $stmt->execute([$chapterId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Brak parametrów']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
