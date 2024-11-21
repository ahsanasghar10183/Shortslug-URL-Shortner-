<?php
 $baseurl  = dirname(__DIR__, 1);

 include ($baseurl . '/includes/head.php');
 ?>
  <body class="with-welcome-text">
   <!-- Navbar here -->
    <?php include ($baseurl . '/includes/header.php')?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
       <!----Sidebar Injecting here--->
       <?php include($baseurl . '/includes/sidebar.php')?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
            <div class="card_title bg-primary text-white  mt-3">
                        <h3 >Edit Telephone Short Link</h3>
                    </div>
                <div class="container dashboard_card">
                <form action="tel_link_handler.php" method="POST" class="mt-4">
                    <div class="form-group">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="+1234567890" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Generate Short Tel Link</button>
                </form>

                <hr class="my-4">
                
                <div id="result" class="mt-4">
                    <!-- Display shortened link here after form submission -->
                </div>
            </div>

            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
              <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
   
  
    <?php include($baseurl. '/includes/postjs.php')?>
  </body>
