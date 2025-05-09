<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Destroy session
session_unset();
session_destroy();

echo json_encode(['message' => 'Logged out successfully']);
?> 