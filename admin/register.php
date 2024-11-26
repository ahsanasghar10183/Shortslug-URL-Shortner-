<!DOCTYPE html>
<html lang="en">
  <?php
    include('../includes/head.php');
    include('./db_connection.php');
    
    // Debugging: Log POST data
    var_dump($_POST);

    $message = ""; // Initialize message

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Check if email already exists
        $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmailStmt->bind_param("s", $email); // Corrected parameter binding
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $message = "Email ID already exists";
        } else {
            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password);

            if ($stmt->execute()) {
                $message = "Account created successfully";
                header('location: ./login.php');
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $checkEmailStmt->close();
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
                <h4>New here?</h4>
                <h6 class="fw-light">Signing up is easy. It only takes a few steps</h6>
                
                <!-- Display message if any -->
                <?php if (!empty($message)) { ?>
                  <div class="alert alert-info"><?php echo $message; ?></div>

                <?php } ?>

                <form class="pt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
               
                  <!-- Email -->
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" required>
                  </div>
                  
                  <!-- Password -->
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                  </div>

                  <!-- Terms and Conditions Checkbox -->
                  <div class="mb-4">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" required> I agree to all Terms & Conditions
                      </label>
                    </div>
                  </div>

                  <!-- Submit Button -->
                  <div class="mt-3 d-grid gap-2">
                    <button class="btn btn-primary btn-lg fw-medium auth-form-btn" type="submit">SIGN UP</button>
                  </div>

                  <!-- Login Link -->
                  <div class="text-center mt-4 fw-light">
                    Already have an account? <a href="login.php" class="text-primary">Login</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
  </body>
</html>
