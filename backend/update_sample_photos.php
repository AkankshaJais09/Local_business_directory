<?php
require_once 'db.php';

// Array of cuisine-specific photo sets
$cuisine_photos = [
    'Italian' => [
        ['filename' => 'la_piazza_1.jpg', 'is_primary' => 1],
        ['filename' => 'la_piazza_2.jpg', 'is_primary' => 0],
        ['filename' => 'la_piazza_3.jpg', 'is_primary' => 0],
        ['filename' => 'la_piazza_4.jpg', 'is_primary' => 0]
    ],
    'Japanese' => [
        ['filename' => 'sakura_1.jpg', 'is_primary' => 1],
        ['filename' => 'sakura_2.jpg', 'is_primary' => 0],
        ['filename' => 'sakura_3.jpg', 'is_primary' => 0],
        ['filename' => 'sakura_4.jpg', 'is_primary' => 0]
    ],
    'Mexican' => [
        ['filename' => 'mariachi_1.jpg', 'is_primary' => 1],
        ['filename' => 'mariachi_2.jpg', 'is_primary' => 0],
        ['filename' => 'mariachi_3.jpg', 'is_primary' => 0],
        ['filename' => 'mariachi_4.jpg', 'is_primary' => 0]
    ],
    'Chinese' => [
        ['filename' => 'dragon_1.jpg', 'is_primary' => 1],
        ['filename' => 'dragon_2.jpg', 'is_primary' => 0],
        ['filename' => 'dragon_3.jpg', 'is_primary' => 0],
        ['filename' => 'dragon_4.jpg', 'is_primary' => 0]
    ],
    'Indian' => [
        ['filename' => 'taj_1.jpg', 'is_primary' => 1],
        ['filename' => 'taj_2.jpg', 'is_primary' => 0],
        ['filename' => 'taj_3.jpg', 'is_primary' => 0],
        ['filename' => 'taj_4.jpg', 'is_primary' => 0]
    ],
    'French' => [
        ['filename' => 'bistro_1.jpg', 'is_primary' => 1],
        ['filename' => 'bistro_2.jpg', 'is_primary' => 0],
        ['filename' => 'bistro_3.jpg', 'is_primary' => 0],
        ['filename' => 'bistro_4.jpg', 'is_primary' => 0]
    ],
    'Mediterranean' => [
        ['filename' => 'med_1.jpg', 'is_primary' => 1],
        ['filename' => 'med_2.jpg', 'is_primary' => 0],
        ['filename' => 'med_3.jpg', 'is_primary' => 0],
        ['filename' => 'med_4.jpg', 'is_primary' => 0]
    ],
    'American' => [
        ['filename' => 'grill_1.jpg', 'is_primary' => 1],
        ['filename' => 'grill_2.jpg', 'is_primary' => 0],
        ['filename' => 'grill_3.jpg', 'is_primary' => 0],
        ['filename' => 'grill_4.jpg', 'is_primary' => 0]
    ],
    'Thai' => [
        ['filename' => 'bangkok_1.jpg', 'is_primary' => 1],
        ['filename' => 'bangkok_2.jpg', 'is_primary' => 0],
        ['filename' => 'bangkok_3.jpg', 'is_primary' => 0],
        ['filename' => 'bangkok_4.jpg', 'is_primary' => 0]
    ]
];

try {
    // Start transaction
    $conn->begin_transaction();

    // Get all restaurants
    $query = "SELECT id, cuisine_type FROM restaurants";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Error fetching restaurants: " . $conn->error);
    }

    // Clear existing photos
    $conn->query("DELETE FROM restaurant_photos");

    // Process each restaurant
    while ($restaurant = $result->fetch_assoc()) {
        $cuisine = $restaurant['cuisine_type'];
        $restaurant_id = $restaurant['id'];

        // Get photo set for this cuisine (fallback to American if cuisine not found)
        $photo_set = isset($cuisine_photos[$cuisine]) ? $cuisine_photos[$cuisine] : $cuisine_photos['American'];

        // Add photos for this restaurant
        foreach ($photo_set as $photo) {
            $photo_url = 'uploads/restaurants/' . $photo['filename'];
            $is_primary = $photo['is_primary'];

            $stmt = $conn->prepare("INSERT INTO restaurant_photos (restaurant_id, photo_url, is_primary) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $restaurant_id, $photo_url, $is_primary);
            
            if (!$stmt->execute()) {
                throw new Exception("Error adding photo for restaurant $restaurant_id: " . $stmt->error);
            }
            
            $stmt->close();
        }
    }

    // Commit transaction
    $conn->commit();
    echo "Successfully updated restaurant photos!\n";

} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    echo "Error: " . $e->getMessage() . "\n";
}

$conn->close();
?> 