<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$conn = getMysqliConnection();

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Podaj email i hasło']);
    exit();
}

$sql = "SELECT * FROM users WHERE email=? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode([
        'success' => true, 
        'role' => $user['role'],
        'userId' => $user['id']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowy email lub hasło']);
}
$conn->close();
?>
