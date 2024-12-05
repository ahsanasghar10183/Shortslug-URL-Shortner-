<?php
$baseurl = dirname(__DIR__, 1);

// Assuming you are fetching the URL details from the database using the short_code
include($baseurl.'/includes/head.php');
include($baseurl.'/db_connection.php');

// Fetch the short_code from the URL parameter
$shortcode = $_GET['shortcode']; // Ensure proper validation and sanitization

// Step 1: Fetch the URL data from the database
$query = "SELECT * FROM urls WHERE short_code = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $shortcode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Short URL not found.";
    exit;
}
else{
$url = $result->fetch_assoc();

}

// Step 2: Check if the URL has password protection
if
(isset($url['link_password']) && !empty($url['link_password'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Step 3: Handle password submission
        $password = $_POST['password'];

        // Check if the password matches
        if ($password === $url['link_password']) {
            // Redirect to the actual URL
            header("Location: " . $url['destination_url']);
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    }
} else {
    // If no password protection, directly redirect
    header("Location: " . $url['destination_url']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Password - URL Shortener</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Enter Link Password</h5>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>

                <div class="text-center mt-3">
                    <a href="/" class="btn btn-link">Back to Homepage</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (optional for dynamic content) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
