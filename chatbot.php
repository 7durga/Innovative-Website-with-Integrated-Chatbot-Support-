<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'chatbot_db';

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');  // Set content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];

    // Check if the message contains an ID
    if (preg_match('/\d+/', $message, $matches)) {
        $person_id = $matches[0];

        // Fetch person details from the database
        $query = "SELECT * FROM persons WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $person_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $person = $result->fetch_assoc();
            $reply = "Person Details:\nName: " . $person['name'] . "\nAge: " . $person['age'] . "\nEmail: " . $person['email'] . "\nPhone: " . $person['phone'] . "\nAddress: " . $person['address'];
        } else {
            $reply = "No person found with ID: " . $person_id;
        }
    } else {
        $reply = "Please provide a valid ID.";
    }

    // Return the response as JSON
    echo json_encode(['reply' => $reply]);
} else {
    echo json_encode(['reply' => 'Invalid request.']);
}

$conn->close();
?>
