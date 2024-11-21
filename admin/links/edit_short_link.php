
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
                        <h3 >Edit Short URL</h3>
                    </div>
                <div class="container dashboard_card">
                    <form>
                        <!-- Link Destination -->
                        <div class="form-group">
                            <label for="linkDestination" class="text-primary">Link Destination</label>
                            <input type="url" class="form-control" id="linkDestination" placeholder="Enter destination URL" required>
                        </div>

                        <!-- Link Name -->
                        <div class="form-group">
                            <label for="linkName" class="text-primary">Link Name</label>
                            <input type="text" class="form-control" id="linkName" placeholder="Enter link name" required>
                        </div>

                        <!-- Link Status -->
                        <div class="form-group">
                            <label class="text-primary" for="linkStatus">Link Status</label><br>
                            <label class="custom-toggle">
                                <input type="checkbox" id="linkStatus" name="linkStatus">
                                <span></span>
                            </label>
                        </div>

                        <!-- Is Cloaking -->
                        <div class="form-group">
                            <label class="text-primary" for="enable_cloaking">Link Cloaking</label><br>
                            <label class="custom-toggle">
                                <input type="checkbox" id="link_cloaking_Status" name="link_cloaking_Status">
                                <span></span>
                            </label>
                        </div>
                        

                        <!-- Social Sharing -->
                        <div class="form-group">
                            <label class="text-primary">Social Sharing</label>
                            <input type="text" class="form-control mb-2" id="socialTitle" placeholder="Title">
                            <input type="text" class="form-control mb-2" id="socialDescription" placeholder="Description">
                            <input type="file" class="form-control-file" id="socialImage">
                        </div>

                        <!-- Expiry Date & Time -->
                        <div class="form-group">
                            <label for="expiryDate" class="text-primary">Expiry Date & Time</label>
                            <input type="datetime-local" class="form-control" id="expiryDate">
                        </div>

                        <!-- Link Destination After Expiry -->
                        <div class="form-group">
                            <label for="linkAfterExpiry" class="text-primary">Link Destination After Expiry</label>
                            <input type="url" class="form-control" id="linkAfterExpiry" placeholder="Enter destination after expiry">
                        </div>

                        <!-- Link Password -->
                        <div class="form-group">
                            <label for="linkPassword" class="text-primary">Link Password</label>
                            <input type="password" class="form-control" id="linkPassword" placeholder="Enter password (optional)">
                        </div>

                          <div class="form-group mb-3">
                            <input type="checkbox" class="form-check-input" style="height:16px; width: 16px;" id="oneTimeView">
                            <label class="form-check-label text-primary ps-3 " style="font-size: 14px;" for="oneTimeView">One-time View Link</label>

                        </div>

                        <!-- One-time View Link -->
                       

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-block">Generate Link</button>
                    </form>
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
