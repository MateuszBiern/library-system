<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

$chapterId = $_GET['chapterId'] ?? null;

if (!$chapterId) {
    http_response_code(400);
    echo json_encode(['error' => 'Brak chapterId']);
    exit;
}

try {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare('SELECT * FROM chapter_content WHERE chapter_id = ?');
    $stmt->execute([$chapterId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Chapter nie znaleziony']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
?>
