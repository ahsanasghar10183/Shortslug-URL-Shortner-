<?php
 $baseurl  = dirname(__DIR__, 1);

 include ($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');

if (isset($_GET['shortcode'])) {
    $shortcode = $_GET['shortcode'];

    // Check if it's a mailto link
   $shortcodequery = "SELECT * FROM urls where short_code = ?";
    $shortcodestmt = $conn->prepare($shortcodequery);
    $shortcodestmt->bind_param("s", $shortcode);
    $shortcodestmt->execute();
    $shortlink = $shortcodestmt->get_result();
    
    $mailtoquery = "SELECT * FROM email_link where short_code = ?";
    $mailtostmt = $conn->prepare($mailtoquery);
    $mailtostmt->bind_param("s", $shortcode);
    $mailtostmt->execute();
    $mailtolink = $mailtostmt->get_result();

    $sms_query = "SELECT * FROM sms_link where short_code = ?";
    $sms_stmt = $conn->prepare($sms_query);
    $sms_stmt->bind_param("s", $shortcode);
    $sms_stmt->execute();
    $sms_link = $sms_stmt->get_result();

    $tel_query = "SELECT * FROM tel_link where short_code = ?";
    $tel_stmt = $conn->prepare($tel_query);
    $tel_stmt->bind_param("s", $shortcode);
    $tel_stmt->execute();
    $tel_link = $tel_stmt->get_result();


    // handlilng Landing page redirection 

    $landinpage_query = "SELECT * FROM landingpages where short_link = ?";
    $landinpage_stmt = $conn->prepare($landinpage_query);
    $landinpage_stmt->bind_param("s", $shortcode);
    $landinpage_stmt->execute();
    $landinpage_link = $landinpage_stmt->get_result();


    if ($shortlink->num_rows > 0) {
        header("Location:" . $baseUrl . "links/url_redirect.php?shortcode=$shortcode"); 
    }
    else if($mailtolink->num_rows>0){
        header("Location:" . $baseUrl . "links/mailto_links_handler.php?shortcode=$shortcode");
    }
    else if($sms_link->num_rows>0){
        header("Location:" . $baseUrl . "links/sms_links_handler.php?shortcode=$shortcode");
    }
    else if($tel_link->num_rows>0){
        header("Location:" . $baseUrl . "links/tel_links_handler.php?shortcode=$shortcode");
    }
    else if($landinpage_link->num_rows>0){
        header("Location:" . $baseUrl . "landingpages/landingpage_handler.php?shortcode=$shortcode");

    }
    
} 
else {
    echo "Shortcode is required.";
}
?>
