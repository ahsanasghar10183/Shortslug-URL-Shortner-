<?php
$baseurl = dirname(__DIR__, 1);
include($baseurl.'/includes/head.php');
include($baseurl . '/db_connection.php');

session_start();
$user_id = $_SESSION['user_id'];

$shortcode = $_GET['shortcode'];

// Step 1: Fetch the URL data from the database
$query = "SELECT * FROM landingpages WHERE user_id = ? && short_link = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $shortcode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Short URL not found.";
    exit;
}


$landing_page_data = $result->fetch_assoc();

// Step 2: Check if the landing_page_data is expired
if ($landing_page_data['expiry_date'] && strtotime($landing_page_data['expiry_date']) < time()) {
    echo "This link has expired.";
    $update_query = "UPDATE landing_page_data SET status = 2 WHERE user_id = ? && short_link = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("is", $user_id, $shortcode); 
    $update_stmt->execute();
    header("Location: ".$landing_page_data['destination_after_expiry']);
    exit;
}

// Step 8: Redirect to the original URL
header("Location: " . $baseUrl ."/landingpages/landingpage.php?shortcode=$shortcode");
exit();
?>
