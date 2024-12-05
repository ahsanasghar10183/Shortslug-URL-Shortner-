<?php
$baseurl = dirname(__DIR__, 1);
$vendorpath = dirname(__DIR__, 2);
include($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');
require_once($vendorpath . '/vendor/phpqrcode/phpqrcode.php');

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied: You are not logged in.");
}

$user_id = $_SESSION['user_id'];
$shortcode = $_GET['shortcode'] ?? ''; // Get the shortcode

if (isset($_GET['shortcode'])) {
    $shortcode = $_GET['shortcode'];
    $short_link_query = "SELECT link_type FROM urls WHERE short_code = ?";
    $short_link_stmt = $conn->prepare($short_link_query);
    $short_link_stmt->bind_param('s', $shortcode);
    $short_link_stmt->execute();
    $shortlink = $short_link_stmt->get_result();


    // querying the email links table
    $mailto_query = "SELECT link_type FROM email_link WHERE short_code = ?";
    $mailto_stmt = $conn->prepare($mailto_query);
    $mailto_stmt->bind_param('s', $shortcode);
    $mailto_stmt->execute();
    $mailto_link = $mailto_stmt->get_result();
    // querying the sms link table
    $sms_query = "SELECT link_type FROM sms_link WHERE short_code = ?";
    $sms_stmt = $conn->prepare($sms_query);
    $sms_stmt->bind_param('s', $shortcode);
    $sms_stmt->execute();
    $sms_short_link = $sms_stmt->get_result();
//    querying the tel link table
    $tel_query = "SELECT link_type FROM tel_link WHERE short_code = ?";
    $tel_stmt = $conn->prepare($tel_query);
    $tel_stmt->bind_param('s', $shortcode);
    $tel_stmt->execute();
    $tel_shortlink = $tel_stmt->get_result();

    if ($shortlink->num_rows > 0) {
        try {
            // Prepare the DELETE query
            $query = "DELETE FROM urls WHERE user_id = ? AND short_code = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $user_id, $shortcode); // Bind parameters: user_id (int) and short_code (string)
            
            // Execute the query
            if ($stmt->execute()) {
                echo "URL with shortcode '$shortcode' deleted successfully.";
                header("Location:" . $baseUrl ."links/view_all_links.php");
            } else {
                echo "Failed to delete URL: " . $stmt->error;
            }
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        } finally {
            $stmt->close();
            $conn->close();
        }

    }
    else if ($mailto_link->num_rows > 0) {
        try {
            // Prepare the DELETE query
            $query = "DELETE FROM email_link WHERE user_id = ? AND short_code = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $user_id, $shortcode); // Bind parameters: user_id (int) and short_code (string)
            
            // Execute the query
            if ($stmt->execute()) {
                echo "URL with shortcode '$shortcode' deleted successfully.";
                header("Location:" . $baseUrl ."links/view_all_links.php");
            } else {
                echo "Failed to delete URL: " . $stmt->error;
            }
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }
    else if ($sms_short_link->num_rows > 0) {
        try {
            // Prepare the DELETE query
            $query = "DELETE FROM sms_link WHERE user_id = ? AND short_code = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $user_id, $shortcode); // Bind parameters: user_id (int) and short_code (string)
            
            // Execute the query
            if ($stmt->execute()) {
                echo "URL with shortcode '$shortcode' deleted successfully.";
                header("Location:" . $baseUrl ."links/view_all_links.php");
            } else {
                echo "Failed to delete URL: " . $stmt->error;
            }
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }
    else if ($tel_shortlink->num_rows > 0) {
        try {
            // Prepare the DELETE query
            $query = "DELETE FROM tel_link WHERE user_id = ? AND short_code = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $user_id, $shortcode); // Bind parameters: user_id (int) and short_code (string)
            
            // Execute the query
            if ($stmt->execute()) {
                echo "URL with shortcode '$shortcode' deleted successfully.";
                header("Location:" . $baseUrl ."links/view_all_links.php");
            } else {
                echo "Failed to delete URL: " . $stmt->error;
            }
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }
    $short_link_stmt->close();
    $mailto_stmt->close();
    $sms_stmt->close();
    $tel_stmt->close();



} else {
    echo "No ID provided.";
    exit;
}
// Validate input
if (empty($shortcode)) {
    die("Invalid request: Shortcode is required.");
}


?>
