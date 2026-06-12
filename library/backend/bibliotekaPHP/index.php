<?php
// API Router
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get the request path
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = str_replace('/api/', '', $request_uri);
$request_method = $_SERVER['REQUEST_METHOD'];

// Simple router
$endpoints = [
    'books' => 'books.php',
    'login' => 'login.php',
    'register' => 'Register.php',
    'add_book' => 'add_book.php',
    'book_manager' => 'book_manager.php',
    'book_chapters' => 'book_chapters.php',
    'chapter_content' => 'chapter_content.php',
    'tags' => 'tags.php',
    'rate_book' => 'rate_book.php',
    'get_ratings' => 'get_ratings.php',
    'get_user_rating' => 'get_user_rating.php',
    'get_recommended_books' => 'get_recommended_books.php',
    'import_json' => 'import_json.php',
];

$path_parts = explode('/', trim($request_uri, '/'));
$endpoint = $path_parts[0] ?? 'books';

if (isset($endpoints[$endpoint]) && file_exists(__DIR__ . '/' . $endpoints[$endpoint])) {
    include __DIR__ . '/' . $endpoints[$endpoint];
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
?>
