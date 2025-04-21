<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['restaurant_id', 'user_id', 'date', 'time', 'people'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Missing required field: $field"]);
        exit();
    }
}

// Sanitize and validate inputs
$restaurant_id = intval($data['restaurant_id']);
$user_id = intval($data['user_id']);
$booking_date = $data['date'];
$booking_time = $data['time'];
$num_people = intval($data['people']);
$notes = isset($data['notes']) ? trim($data['notes']) : '';

// Validate date and time
$booking_datetime = strtotime("$booking_date $booking_time");
if ($booking_datetime === false || $booking_datetime < time()) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid booking date/time']);
    exit();
}

// Validate number of people
if ($num_people < 1 || $num_people > 8) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Number of people must be between 1 and 8']);
    exit();
}

try {
    // First, check if the restaurant exists
    $stmt = $conn->prepare("SELECT id FROM restaurants WHERE id = ?");
    $stmt->bind_param('i', $restaurant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Restaurant not found');
    }

    // Check if the time slot is available
    $stmt = $conn->prepare("SELECT id FROM bookings WHERE restaurant_id = ? AND booking_date = ? AND booking_time = ? AND status != 'cancelled'");
    $stmt->bind_param('iss', $restaurant_id, $booking_date, $booking_time);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        throw new Exception('This time slot is already booked');
    }

    // Create the booking
    $stmt = $conn->prepare("INSERT INTO bookings (restaurant_id, user_id, booking_date, booking_time, num_people, notes, status) VALUES (?, ?, ?, ?, ?, ?, 'confirmed')");
    $stmt->bind_param('iissis', $restaurant_id, $user_id, $booking_date, $booking_time, $num_people, $notes);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to create booking');
    }

    $booking_id = $stmt->insert_id;

    echo json_encode([
        'status' => 'success',
        'message' => 'Booking confirmed successfully',
        'booking_id' => $booking_id
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