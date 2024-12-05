<?php
$baseurl  = dirname(__DIR__, 1);

include ($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');

if (isset($_GET['shortcode'])) {
    $shortcode = $_GET['shortcode'];

    // Retrieve the email link details based on the shortcode
    $query = "SELECT * FROM email_link WHERE short_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $shortcode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Extract details for the mailto link
        $receiver = htmlspecialchars($row['reciever']);
        $subject = htmlspecialchars($row['subject']);
        $body = htmlspecialchars($row['body']);

        // Create the mailto URL
        $mailtoUrl = "mailto:$receiver?subject=" . ($subject) . "&body=" . urlencode($body);

        // Redirect to the mailto URL
        header("Location: $mailtoUrl");
        exit;
    } else {
        echo "Invalid shortcode or email link not found.";
    }
} else {
    echo "Shortcode is required.";
}
?>
