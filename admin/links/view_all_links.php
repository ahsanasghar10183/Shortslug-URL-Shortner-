
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
             <div class="container mt-3">
                <div class="card">
                    <div class="card-header bg-primary text-white p-3">
                        <h3>Manage All Links</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                         <div class="d-flex mb-3 align-items-end">
                            <!-- Filter by Status -->
                            <div class="me-3">
                                <label for="statusFilter" class="text-primary mb-2">Filter by Status</label>
                                <select class="form-control" id="statusFilter" name="statusFilter">
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>

                            <!-- Filter by Protection -->
                            <div class="me-3">
                            <label for="statusFilter" class="text-primary mb-2">Filter by Status</label>
                                <select class="form-control" id="protectionFilter" name="protectionFilter">
                                    <option value="all" class="option_select">All</option>
                                    <option value="password_protected" class="option_select">Password Protected</option>
                                    <option value="one_time_view" class="option_select">One-Time View</option>
                                    <option value="one_time_view" class="option_select">Call to Action Links</option>
                                </select>
                            </div>

                           
                        
                            
                            <!-- Apply Filters Button -->
                            <div class="ms-auto">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Link Name</th>
                                    <th>Short URL</th>
                                    <th>Destination</th>
                                    <th>Creation Date</th>
                                    <th>Expiry Date</th>
                                    <th>Details</th>
                                    <th>Analytics</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example Row (Repeat for each link in your database) -->
                        
                                <?php
                                for($i=0; $i<10; $i++){
?>
                                    <tr>
                                    <td class="text-center">Sample Link</td>
                                    <td class="text-center"><a href="shorturl.test/abc123" target="_blank">shorturl.test/abc123</a></td>
                                    <td class="text-center">https://example.com/destination-page</td>
                                    <td class="text-center">2023-01-01</td>
                                    <td class="text-center">2023-06-01</td>
                                    <td class="text-center"> <a class="link_crud_icons" href="<?php echo $baseUrl ?>links/details.php"  title="View">
                                           View
                                        </a>
                                     </td>
                                    <td class="text-center"><a href="#" style="text-center"> <i class="fas fa-bar-chart " style="color: blue; font-size: 18px;"></i></a></td>

                                    <td class="text-center text-sm"><span class="badge badge-success">Active</span></td>
                                  
                                    <td class="text-center">
                                       
                                        <a class="link_crud_icons" href="<?php echo $baseUrl?>links/edit_short_link.php" title="Edit">
                                            <i class="fas fa-edit text-warning" ></i>
                                        </a>
                                        <a class="link_crud_icons" href="#" title="Delete">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                                <!-- Repeat rows for each link -->
                            </tbody>
                        </table>
                    </div>
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
