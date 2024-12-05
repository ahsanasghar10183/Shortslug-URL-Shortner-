
<?php
 $baseurl  = dirname(__DIR__, 1);

 include ($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}
$user_id = $_SESSION['user_id'];


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
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-scroll">
                            
                                    <!-- Example Row (Repeat for each link in your database) -->
                                
                                    <?php
                             
                                    $query = "SELECT * FROM landingpages where user_id = $user_id";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>

                                    <thead class="thead-light">
                                    <tr>
                                        <th>Page Name</th>
                                        <th>Short URL</th>
                                        <th>Destination</th>
                                        <th>Expiry Date</th>
                                        <th>Destination After Expiry</th>
                                        <th>Status</th>
                                        <th>Copy</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $row['Name']?></td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $row['short_link']); ?>" target="_blank"> <?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ."/".$row['short_link']); ?></a></td>
                                            <td class="text-center"><?php echo $row['destination_link']?></td>
                                            <td class="text-center"><?php echo $row['expirydate']?></td>
                                            <td class="text-center"><?php echo $row['destination_after_expiry']?></td>
                                            <td class="text-center text-sm"><span class="badge <?php echo $row['status'] >= 2 ? 'badge-danger' : ($row['status'] ? 'badge-success' : 'badge-secondary'); ?>">
                                                <?php echo $row['status'] >=2 ? "Expired" : ($row['status']? "Active" : "Inactive"); ?></span>
                                            </td>
        
                                            <td class="text-center">
                                            <a href="#" onclick="copyToClipboard('<?php echo addslashes($_SERVER['HTTP_HOST'] . '/' . $row['short_link']); ?>')" title="Copy Short URL">
                                                    <i class="fas fa-copy" style="color: green; font-size: 18px;"></i>
                                                </a>
                                    
                                            </td>
                                            <td class="text-center">
                                                <a class="link_crud_icons"  href="<?php echo htmlspecialchars($baseUrl . 'landingpages/delete.php?shortcode=' . $row['short_link']);?>" title="Delete">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    
                                    <?php
                                    }
                                
                           
                                ?>    
                                    <!-- Repeat rows for each link -->
                                </tbody>
                            </table>
                        </div>
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
