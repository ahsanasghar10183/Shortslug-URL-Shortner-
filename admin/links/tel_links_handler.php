<?php
$baseurl  = dirname(__DIR__, 1);

include ($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');
// Get the short code from the URL
if (isset($_GET['shortcode']) && !empty($_GET['shortcode'])) {
    $shortCode = htmlspecialchars($_GET['shortcode']);
    
    // Query to fetch the receiver's phone number using the short code
    $query = "SELECT reciever FROM tel_link WHERE short_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $shortCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the short code exists in the database
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $receiverPhone = $row['reciever'];
        
        // Validate the receiver's phone number
        if (!empty($receiverPhone)) {
            // Redirect to a tel link to initiate the call
            header("Location: tel:$receiverPhone");
            exit;
        } else {
            echo "Phone number not found for the provided short link.";
        }
    } else {
        echo "Invalid short code.";
    }
    
    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "No short code provided.";
}
