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

if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID is required']);
    exit();
}

$user_id = intval($_GET['user_id']);

try {
    // Updated query to include photos
    $query = "SELECT r.*, 
              GROUP_CONCAT(
                  JSON_OBJECT(
                      'id', rp.id,
                      'photo_url', rp.photo_url,
                      'is_primary', CAST(rp.is_primary AS CHAR)
                  )
              ) as photos
              FROM restaurants r
              LEFT JOIN restaurant_photos rp ON r.id = rp.restaurant_id
              WHERE r.user_id = ?
              GROUP BY r.id
              ORDER BY r.created_at DESC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        throw new Exception($conn->error);
    }
    
    $restaurants = [];
    while ($row = $result->fetch_assoc()) {
        // Parse photos JSON string
        $photos = [];
        if ($row['photos']) {
            $photos_json = '[' . str_replace('}{', '},{', $row['photos']) . ']';
            $photos = json_decode($photos_json, true);
        }
        
        // Remove the raw photos string and add parsed photos
        unset($row['photos']);
        $row['photos'] = $photos;
        
        // Convert rating to float
        $row['rating'] = floatval($row['rating']);
        
        $restaurants[] = $row;
    }
    
    echo json_encode(['restaurants' => $restaurants]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch restaurants: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 