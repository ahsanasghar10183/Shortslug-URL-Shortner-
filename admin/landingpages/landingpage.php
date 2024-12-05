<?php
$baseurl = dirname(__DIR__, 1);
include($baseurl.'/includes/head.php');
include($baseurl . '/db_connection.php');

session_start();
$user_id = $_SESSION['user_id'];

$shortcode = $_GET['shortcode'];

if (!$shortcode) {
    die("Invalid or missing short link.");
}

// Fetch landing page data based on the short link
$query = "SELECT * FROM landingpages WHERE short_link = ? AND status = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $shortcode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Landing page not found or inactive.");
}

$landing_page = $result->fetch_assoc();
$backgroundColor = $landing_page['background_color'] ?? '#fff';
$titleColor = $landing_page['title_color'] ?? '#1F3BB3';
$buttonColor = $landing_page['button_color'] ?? '#1F3BB3';
$buttonHoverColor = $landing_page['button_hover_color'] ?? '#1b3298';
// Check if the page has expired

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($landing_page['title']); ?></title>
    <style>
 body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .template-card {
            background-color: <?php echo $backgroundColor; ?>; /* Dynamic background color */
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease;
            max-width: 400px;
            height: 550px;
            margin: auto;
            text-align: center;
            padding: 16px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .template-card.selected {
            border: 2px solid <?php echo $titleColor; ?>; /* Dynamic border color */
            transform: scale(1.1);
        }
        .template-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .template-card img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        .template-card h5 {
            font-size: 1.5rem;
            font-weight: bold;
            color: <?php echo $titleColor; ?>; /* Dynamic title color */
            margin-bottom: 8px;
        }
        .template-card p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
        }
        .template-card .cta-button {
            background-color: <?php echo $buttonColor; ?>; /* Dynamic button color */
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .template-card .cta-button:hover {
            background-color: <?php echo $buttonHoverColor; ?>; /* Dynamic hover color */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="template-card">
        <img src="<?php echo $baseUrl ?>assets/images/fashion.jpg" alt="Bakery">
          <h5><?php echo htmlspecialchars($landing_page['title']); ?></h5>
            <p><?php echo htmlspecialchars($landing_page['description']); ?></p>
            <a href="<?php echo htmlspecialchars($landing_page['destination_link']); ?>" class="cta-button">
                <?php echo htmlspecialchars($landing_page['button_text']); ?>
            </a>
        </div>
    </div>
</body>
</html>
