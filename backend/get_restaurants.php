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

try {
    // Get all restaurants with their photos
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
              GROUP BY r.id
              ORDER BY r.created_at DESC";
              
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Failed to fetch restaurants');
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

    // Return the restaurants array
    echo json_encode(['restaurants' => $restaurants]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?> 