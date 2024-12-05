<?php
$baseurl = dirname(__DIR__, 1);
$vendorpath = dirname(__DIR__, 2);
include($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied: You are not logged in.");
}

$user_id = $_SESSION['user_id'];
$shortcode = $_GET['shortcode'] ?? ''; // Get the shortcode

if (isset($_GET['shortcode'])) {
    $shortcode = $_GET['shortcode'];
    $query = "Delete FROM landingpages WHERE short_link = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $shortcode);
    $stmt->execute();
    $landingpage = $stmt->get_result();
       try{
            // Execute the query
            if ($stmt->execute()) {
                echo "Landing with shortcode '$shortcode' deleted successfully.";
                header("Location:" . $baseUrl ."landingpages/view.php");
            } else {
                echo "Failed to delete Landing Page: " . $stmt->error;
            }
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }
// Validate input
if (empty($shortcode)) {
    die("Invalid request: Shortcode is required.");
}


?>
