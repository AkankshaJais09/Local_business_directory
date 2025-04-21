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
$required_fields = ['id', 'name', 'cuisine_type', 'description', 'address', 'contact_number', 'rating', 'user_id'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit();
    }
}

// Validate rating
$rating = floatval($data['rating']);
if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Rating must be between 1 and 5']);
    exit();
}

try {
    // Verify ownership
    $stmt = $conn->prepare("SELECT user_id FROM restaurants WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Restaurant not found']);
        exit();
    }
    
    $restaurant = $result->fetch_assoc();
    if ($restaurant['user_id'] != $data['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'You do not have permission to update this restaurant']);
        exit();
    }
    $stmt->close();

    // Sanitize inputs
    $id = intval($data['id']);
    $name = sanitize_input($data['name']);
    $cuisine_type = sanitize_input($data['cuisine_type']);
    $description = sanitize_input($data['description']);
    $address = sanitize_input($data['address']);
    $contact_number = sanitize_input($data['contact_number']);
    $user_id = intval($data['user_id']);

    // Update restaurant
    $stmt = $conn->prepare("UPDATE restaurants SET name = ?, cuisine_type = ?, description = ?, address = ?, contact_number = ?, rating = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssssdii", $name, $cuisine_type, $description, $address, $contact_number, $rating, $id, $user_id);

    if ($stmt->execute()) {
        echo json_encode([
            'message' => 'Restaurant updated successfully',
            'restaurant' => [
                'id' => $id,
                'name' => $name,
                'cuisine_type' => $cuisine_type,
                'description' => $description,
                'address' => $address,
                'contact_number' => $contact_number,
                'rating' => $rating,
                'user_id' => $user_id
            ]
        ]);
    } else {
        throw new Exception('Failed to update restaurant');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 