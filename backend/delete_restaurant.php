<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit();
}

// Validate required fields
if (!isset($data['id']) || !isset($data['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Restaurant ID and User ID are required']);
    exit();
}

$id = intval($data['id']);
$user_id = intval($data['user_id']);

try {
    // Verify ownership
    $stmt = $conn->prepare("SELECT user_id FROM restaurants WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Restaurant not found']);
        exit();
    }
    
    $restaurant = $result->fetch_assoc();
    if ($restaurant['user_id'] != $user_id) {
        http_response_code(403);
        echo json_encode(['error' => 'You do not have permission to delete this restaurant']);
        exit();
    }
    $stmt->close();

    // Delete restaurant
    $stmt = $conn->prepare("DELETE FROM restaurants WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Restaurant deleted successfully']);
    } else {
        throw new Exception('Failed to delete restaurant');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 