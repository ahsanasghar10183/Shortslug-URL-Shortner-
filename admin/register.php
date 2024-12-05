<?php
include('../includes/head.php');
include('./db_connection.php');

$message = ""; // Initialize the message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            // Insert the new user
            $insertStmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $insertStmt->bind_param("ss", $email, $hashed_password);

            if ($insertStmt->execute()) {
                $message = "Account created successfully! Redirecting to login...";
                header('Refresh: 2; URL=login.php'); // Redirect after 2 seconds
                exit;
            } else {
                $message = "Error: " . $insertStmt->error;
            }

            $insertStmt->close();
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields!";
    }

    $conn->close();
}
?>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5 rounded-4">
                            <div class="brand-logo">
                                <img src="../assets/images/short-slug-logo.png" alt="logo">
                            </div>
                            <h4>Create a new account</h4>
                            <h6 class="fw-light">It's quick and easy!</h6>
                            <?php if (!empty($message)): ?>
                                <div class="alert alert-info">
                                    <?= htmlspecialchars($message); ?>
                                </div>
                            <?php endif; ?>
                            <form class="pt-3" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-check mb-4">
                                    <input type="checkbox" class="form-check-input" required>
                                    <label class="form-check-label">I agree to all Terms & Conditions</label>
                                </div>
                                <div class="mt-3 d-grid gap-2">
                                    <button class="btn btn-primary btn-lg fw-medium auth-form-btn" type="submit">Sign Up</button>
                                </div>
                                <div class="text-center mt-4 fw-light">
                                    Already have an account? <a href="login.php" class="text-primary">Sign In</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
