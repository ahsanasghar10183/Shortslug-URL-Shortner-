<?php
$baseurl  = dirname(__DIR__, 1);

include ($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');

// Get the shortcode from the URL
if (isset($_GET['shortcode'])) {
    $shortcode = $_GET['shortcode'];

    // Fetch the corresponding SMS link details from the database
    $query = "SELECT * FROM sms_link WHERE short_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $shortcode);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the shortcode exists, fetch the details
    if ($row = $result->fetch_assoc()) {
        $reciever = $row['reciever'];  // The phone number to send the SMS to
        $message = $row['message'];    // The message content

        // Generate the SMS link
        $smsLink = "sms:$reciever?body=" . urlencode($message);

        // Redirect to the default SMS app
        header("Location: $smsLink");
        exit;
    } else {
        // If no matching shortcode, redirect to a 404 or an error page
        echo "Invalid shortcode!";
    }
} else {
    // If no shortcode is provided, redirect to the homepage or an error page
    echo "Shortcode not found!";
}
?>
