<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $pdo = getDbConnection();

    $stmt = $pdo->query('
        SELECT b.*, GROUP_CONCAT(t.name) as tags
        FROM books b
        LEFT JOIN book_tags bt ON b.id = bt.book_id
        LEFT JOIN tags t ON bt.tag_id = t.id
        GROUP BY b.id
        ORDER BY b.title
    ');
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($results as &$book) {
        $book['tags'] = $book['tags'] ? explode(',', $book['tags']) : [];
        
        if (!empty($book['cover_image'])) {
            if (strpos($book['cover_image'], 'http') === false) {
                $book['cover_image'] = '/book_covers/' . basename($book['cover_image']);
            }
        }
    }
    
    echo json_encode($results);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
