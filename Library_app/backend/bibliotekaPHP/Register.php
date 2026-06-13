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

    // Sprawdź czy istnieje
    $sqlCheck = "SELECT id FROM users WHERE email=? LIMIT 1";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email już istnieje']);
        exit();
    }

    // Hashowanie hasła
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, 'users')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Zarejestrowano pomyślnie']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Błąd przy rejestracji']);
    }

    $conn->close();
?>
