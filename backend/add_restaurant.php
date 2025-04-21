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

// Create uploads directory if it doesn't exist
$upload_dir = '../uploads/restaurants/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Validate required fields
$required_fields = ['name', 'cuisine_type', 'description', 'address', 'contact_number', 'rating', 'user_id'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit();
    }
}

// Validate rating
$rating = floatval($_POST['rating']);
if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Rating must be between 1 and 5']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Sanitize inputs
    $name = sanitize_input($_POST['name']);
    $cuisine_type = sanitize_input($_POST['cuisine_type']);
    $description = sanitize_input($_POST['description']);
    $address = sanitize_input($_POST['address']);
    $contact_number = sanitize_input($_POST['contact_number']);
    $user_id = intval($_POST['user_id']);

    // Insert restaurant
    $stmt = $conn->prepare("INSERT INTO restaurants (name, cuisine_type, description, address, contact_number, rating, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdi", $name, $cuisine_type, $description, $address, $contact_number, $rating, $user_id);

    if (!$stmt->execute()) {
        throw new Exception('Failed to add restaurant');
    }

    $restaurant_id = $stmt->insert_id;
    $stmt->close();

    // Handle photo uploads
    $photos = [];
    
    // Handle primary photo
    if (isset($_FILES['primary_photo']) && $_FILES['primary_photo']['error'] === UPLOAD_ERR_OK) {
        $photo_url = handlePhotoUpload($_FILES['primary_photo'], $upload_dir, $restaurant_id);
        if ($photo_url) {
            $stmt = $conn->prepare("INSERT INTO restaurant_photos (restaurant_id, photo_url, is_primary) VALUES (?, ?, 1)");
            $stmt->bind_param("is", $restaurant_id, $photo_url);
            $stmt->execute();
            $stmt->close();
            $photos[] = ['url' => $photo_url, 'is_primary' => 1];
        }
    }

    // Handle additional photos
    if (isset($_FILES['additional_photos'])) {
        $file_count = count($_FILES['additional_photos']['name']);
        for ($i = 0; $i < min($file_count, 4); $i++) {
            $file = [
                'name' => $_FILES['additional_photos']['name'][$i],
                'type' => $_FILES['additional_photos']['type'][$i],
                'tmp_name' => $_FILES['additional_photos']['tmp_name'][$i],
                'error' => $_FILES['additional_photos']['error'][$i],
                'size' => $_FILES['additional_photos']['size'][$i]
            ];
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $photo_url = handlePhotoUpload($file, $upload_dir, $restaurant_id);
                if ($photo_url) {
                    $stmt = $conn->prepare("INSERT INTO restaurant_photos (restaurant_id, photo_url, is_primary) VALUES (?, ?, 0)");
                    $stmt->bind_param("is", $restaurant_id, $photo_url);
                    $stmt->execute();
                    $stmt->close();
                    $photos[] = ['url' => $photo_url, 'is_primary' => 0];
                }
            }
        }
    }

    // Commit transaction
    $conn->commit();

    http_response_code(201);
    echo json_encode([
        'message' => 'Restaurant added successfully',
        'restaurant' => [
            'id' => $restaurant_id,
            'name' => $name,
            'cuisine_type' => $cuisine_type,
            'description' => $description,
            'address' => $address,
            'contact_number' => $contact_number,
            'rating' => $rating,
            'user_id' => $user_id,
            'photos' => $photos
        ]
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();

// Helper function to handle photo uploads
function handlePhotoUpload($file, $upload_dir, $restaurant_id) {
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5MB

    // Validate file type and size
    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }
    if ($file['size'] > $max_size) {
        return false;
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $restaurant_id . '_' . uniqid() . '.' . $extension;
    $filepath = $upload_dir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'uploads/restaurants/' . $filename;
    }

    return false;
}
?> 