<?php
include('../includes/head.php');
include('./db_connection.php');

session_start(); // Start session management
$message = ""; // Initialize the message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_email, $db_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $db_password)) {
                // Set session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $db_email;

                // Redirect to the dashboard
                header('Location: index.php');
                exit;
            } else {
                $message = "Invalid email or password!";
            }
        } else {
            $message = "Invalid email or password!";
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
                            <h4>Welcome back!</h4>
                            <h6 class="fw-light">Sign in to your account.</h6>
                            <?php if (!empty($message)): ?>
                                <div class="alert alert-danger">
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
                                <div class="mt-3 d-grid gap-2">
                                    <button class="btn btn-primary btn-lg fw-medium auth-form-btn" type="submit">Sign In</button>
                                </div>
                                <div class="text-center mt-4 fw-light"> 
                                    Don't have an account? <a href="./register.php" class="text-primary">Sign Up</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
