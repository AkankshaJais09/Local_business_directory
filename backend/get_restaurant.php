<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Check if restaurant ID is provided
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Restaurant ID is required']);
    exit();
}

$restaurant_id = intval($_GET['id']);

try {
    // Get restaurant with its photos
    $query = "SELECT r.*, 
              GROUP_CONCAT(
                  JSON_OBJECT(
                      'id', rp.id,
                      'photo_url', rp.photo_url,
                      'is_primary', CAST(rp.is_primary AS CHAR)
                  )
              ) as photos,
              u.full_name as owner_name
              FROM restaurants r
              LEFT JOIN restaurant_photos rp ON r.id = rp.restaurant_id
              LEFT JOIN users u ON r.user_id = u.id
              WHERE r.id = ?
              GROUP BY r.id";
              
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

    $stmt->bind_param('i', $restaurant_id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to execute query: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception('Failed to get result: ' . $stmt->error);
    }

    $restaurant = $result->fetch_assoc();
    
    if (!$restaurant) {
        http_response_code(404);
        echo json_encode(['error' => 'Restaurant not found']);
        exit();
    }

    // Parse photos JSON string
    $photos = [];
    if ($restaurant['photos']) {
        $photos_json = '[' . str_replace('}{', '},{', $restaurant['photos']) . ']';
        $photos = json_decode($photos_json, true);
    }
    unset($restaurant['photos']); // Remove the raw photos string
    
    // Add parsed photos to restaurant data
    $restaurant['photos'] = $photos;
    
    // Convert rating to float
    $restaurant['rating'] = floatval($restaurant['rating']);

    echo json_encode([
        'status' => 'success',
        'restaurant' => $restaurant
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 