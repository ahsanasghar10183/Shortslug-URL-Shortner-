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
                
                <div class="container mt-3 dashboard_card">
                    <h2 class="text-left ">Shorten & Track Mailto Links</h2>
                    <form action="mailto_link_handler.php" method="POST" class="mt-4">
                        <div class="form-group">
                            <label for="toEmail">To (Email Address)</label>
                            <input type="email" id="toEmail" name="toEmail" class="form-control" placeholder="example@domain.com" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter email subject">
                        </div>
                        <div class="form-group">
                            <label for="body">Body Text</label>
                            <textarea id="body" name="body" class="form-control" rows="4" maxlength="1000" placeholder="Enter email body text (max 1000 characters)"></textarea>
                            <small class="form-text text-muted">Note: Different browsers may have limits on body length.</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Generate Short Mailto Link</button>
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