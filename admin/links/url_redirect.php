<?php
$baseurl = dirname(__DIR__, 1);
include($baseurl.'/includes/head.php');
include($baseurl . '/db_connection.php');

session_start();
$user_id = $_SESSION['user_id'];

$shortcode = $_GET['shortcode'];

// Step 1: Fetch the URL data from the database
$query = "SELECT * FROM urls WHERE user_id = ? && short_code = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $shortcode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Short URL not found.";
    exit;
}
else{
    $update_hits_query = "UPDATE urls SET hits = hits + 1 WHERE short_code = ?";
    $update_hits_stmt = $conn->prepare($update_hits_query);
    $update_hits_stmt->bind_param("s", $shortcode);
}

$url = $result->fetch_assoc();

// Step 2: Check if the URL is expired
if ($url['expiry_date'] && strtotime($url['expiry_date']) < time()) {
    echo "This link has expired.";
    $update_query = "UPDATE urls SET status = 2 WHERE user_id = ? && short_code = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("is", $user_id, $shortcode); 
    $update_stmt->execute();
    header("Location: ".$url['destination_after_expiry']);
    exit;
}

// Step 3: Handle one-time view links
if ($url['one_time_view'] && $url['one_time_view'] == 1) {
    if ($url['view_count'] > 0) {
        echo "This link has already been used and is no longer available.";
        exit;
    }

    // Mark the link as used
    $update_query = "UPDATE urls SET view_count = view_count + 1, status = 2 WHERE user_id = ? && short_code = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("is", $user_id, $shortcode);
    $update_stmt->execute();
}

// Step 4: Handle password protection (if enabled)
if (isset($url['link_password'])) {
    // Redirect to password form
    header("Location:" . $baseUrl . "links/link_password.php?shortcode=$shortcode");
    exit;
}

// Step 5: Apply country-based redirection
$user_country = getUserCountry(); // Use IP-based country lookup, you can integrate a service like reinvex/laravel-country here.
if ($url['country_target'] && $url['country_target'] != $user_country) {
    echo "Redirecting to a different URL based on country targeting.";
    exit;
}

// Step 6: Apply device-based redirection
$user_device = getUserDevice(); // You can use the `User-Agent` header or a library to detect the device
if ($url['device_target'] && strpos($user_device, $url['device_target']) === false) {
    echo "This link is not available for your device.";
    exit;
}

// Step 7: Increment hits count

// Step 8: Redirect to the original URL
header("Location: " . $url['destination_url']);
exit();

function getUserCountry() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $country = 'Unknown'; // Default if IP lookup fails
  
    $api_url = "http://ip-api.com/json/{$ip}";
    $response = file_get_contents($api_url);
    $data = json_decode($response, true);
    if (isset($data['country'])) {
        $country = $data['country'];
    }
    return $country;
}

function getUserDevice() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/Mobile|Android|iPhone|iPad/', $user_agent)) {
        return 'Mobile';
    }
    return 'Desktop';
}
?>
