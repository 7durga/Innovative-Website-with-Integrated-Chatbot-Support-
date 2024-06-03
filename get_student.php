<?php
header('Content-Type: application/json');

// Include the database connection file
include 'config.php';

// Read the input data from the POST request
$input = json_decode(file_get_contents('php://input'), true);

// Check if registration_number is present in the input data
if (isset($input['registration_number'])) {
    $registration_number = $input['registration_number'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM students WHERE registration_number = ?");
    $stmt->bind_param("s", $registration_number);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a student is found
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $student]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Student not found']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Registration number not provided']);
}
?>
