<!DOCTYPE html>
<html lang="en">
  <?php
   include('../includes/head.php');
  include('./db_connection.php');
  if($_SERVER['REQUEST_METHOD']== 'POST'){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $checkEmailstmt = prepare("SELECT email from users WHERE email = ?");
    $checkEmailstmt->bindparams('s', $email);
    $checkEmailstmt->execute();
    $checkEmailstmt->store_result();
    if ($checkEmailStmt->num_rows > 0) {
      $message = "Email ID already exists";
      
  } else {
      // Prepare and bind
      $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
      $stmt->bind_param("ss", $email, $password);

      if ($stmt->execute()) {
          $message = "Account created successfully";
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
        <div class="content-wrapper d-flex align-items-center auth px-0" >
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto " >
              <div class="auth-form-light text-left py-5 px-4 px-sm-5 rounded-4">
                <div class="brand-logo">
                  <img src="../assets/images/short-slug-logo.png" alt="logo">
                </div>
                <h4>New here?</h4>
                <h6 class="fw-light">Signing up is easy. It only takes a few steps</h6>
                <form class="pt-3" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST">
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" id="email" placeholder="Email">
                  </div>
                  
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="password" placeholder="Password">
                  </div>
                  <div class="mb-4">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" style="opacity: 1;"> I agree to all Terms & Conditions </label>
                    </div>
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <a class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn" type="submit" >SIGN UP</a>
                  </div>
                  <div class="text-center mt-4 fw-light"> Already have an account? <a href="login.php" class="text-primary">Login</a>
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
    <!-- container-scroller -->
    <!-- plugins:js -->
    <!-- <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script> -->
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <!-- <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/template.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/todolist.js"></script> -->
    <!-- endinject -->
  </body>
</html>
