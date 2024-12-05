
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
$link_type_filter = isset($_POST['link_type_filter']) ? $_POST['link_type_filter'] : 'shortlinks'; // Default filter is 'shortlinks'


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
                       
                            <!-- Filter form -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <!-- Filter by Link Type -->
                               <div class="d-flex mb-3 align-items-end">
                                <div class="me-3">
                                    <label for="link_type_filter" class="text-primary mb-2">Link Type</label>
                                    <select class="form-control" id="link_type_filter" name="link_type_filter">
                                        <option value="shortlinks" <?php echo (isset($_POST['link_type_filter']) && $_POST['link_type_filter'] == 'shortlinks') ? 'selected' : ''; ?>>Short Links</option>
                                        <option value="mailto_link" <?php echo (isset($_POST['link_type_filter']) && $_POST['link_type_filter'] == 'mailto_link') ? 'selected' : ''; ?>>Email Links</option>
                                        <option value="sms_link" <?php echo (isset($_POST['link_type_filter']) && $_POST['link_type_filter'] == 'sms_link') ? 'selected' : ''; ?>>SMS Links</option>
                                        <option value="tel_link" <?php echo (isset($_POST['link_type_filter']) && $_POST['link_type_filter'] == 'tel_link') ? 'selected' : ''; ?>>Tel Links</option>
                                    </select>
                                </div>

                                <!-- Apply Filters Button -->
                                <div class="ms-auto">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                              </div>

                            </form>

                        </div>
                        <div class="table-responsive">
                            <?php
                            
                            // Check if there is a session message to display
                            if (isset($_SESSION['bulk_upload_message'])) {
                                // Display the bulk_upload_message in a Bootstrap alert box
                                echo '<div class="alert alert-success" role="alert">' . $_SESSION['bulk_upload_message'] . '</div>';
                                // Unset the bulk_upload_message so it only appears once
                                unset($_SESSION['bulk_upload_message']);
                            }
                        
                            ?>
                            <table class="table table-bordered table-hover table-scroll">
                            
                                    <!-- Example Row (Repeat for each link in your database) -->
                                
                                    <?php
                                if($link_type_filter=="shortlinks"){
                                    $query = "SELECT * FROM urls where user_id = $user_id";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>

                                    <thead class="thead-light">
                                    <tr>
                                        <th>Link Name</th>
                                        <th>Short URL</th>
                                        <th>Destination</th>
                                        <th>Expiry Date</th>
                                        <th>Details</th>
                                        <th>Analytics</th>
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
                                            <td class="text-center"><?php echo $row['link_name']?></td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $row['short_code']); ?>" target="_blank"> <?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ."/".$row['short_code']); ?></a></td>
                                            <td class="text-center"><?php echo $row['destination_url']?></td>
                                            <td class="text-center"><?php echo $row['expiry_date']?></td>
                                            <td class="text-center"> <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?shortcode=' . $row['short_code']); ?>"  title="View">
                                                View
                                                </a>
                                            </td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'index.php?shortcode=' . $row['short_code']); ?>"  style="text-center"> <i class="fas fa-bar-chart " style="color: blue; font-size: 18px;"></i></a></td>
        
                                            <td class="text-center text-sm"><span class="badge <?php echo $row['status'] >= 2 ? 'badge-danger' : ($row['status'] ? 'badge-success' : 'badge-secondary'); ?>">
                                                <?php echo $row['status'] >=2 ? "Expired" : ($row['status']? "Active" : "Inactive"); ?></span>
                                            </td>
                                            <td class="text-center">
                                            <a href="#" onclick="copyToClipboard('<?php echo addslashes($_SERVER['HTTP_HOST'] . '/' . $row['short_code']); ?>')" title="Copy Short URL">
                                                    <i class="fas fa-copy text-primary" style=" font-size: 18px;"></i>
                                                </a>
                                    
                                            </td>
                                            <td class="text-center">
                                            
                                                <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/edit_short_link.php?shortcode=' . $row['short_code']);?>" title="Edit">
                                                    <i class="fas fa-edit text-warning" ></i>
                                                </a>
                                                <a class="link_crud_icons"  href="<?php echo htmlspecialchars($baseUrl . 'links/delete_shortlink.php?shortcode=' . $row['short_code']);?>" title="Delete">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    
                                    <?php
                                    }
                                    
                                }
                                
                                else if($link_type_filter=="mailto_link"){
                                    $query = "SELECT * FROM email_link where user_id = $user_id";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>

                                    <thead class="thead-light">
                                    <tr>
                                        <th>Link Name</th>
                                        <th>Short URL</th>
                                        <th>Reciever</th>
                                        <th>Subject</th>
                                        <th>Body</th>
                                        <th>Details</th>
                                        <th>Analytics</th>
                                        <th>Copy</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $row['link_name']?></td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $row['short_code']); ?>" target="_blank"> <?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ."/".$row['short_code']); ?></a></td>
                                            <td class="text-center"><?php echo $row['reciever']?></td>
                                            <td class="text-center"><?php echo $row['subject']?></td>
                                            <td class="text-center"><?php echo $row['body']?></td>

                                            <td class="text-center"> <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?shortcode=' . $row['short_code']); ?>"  title="View">
                                                View
                                                </a>
                                            </td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'index.php?shortcode=' . $row['short_code']); ?>" style="text-center"> <i class="fas fa-bar-chart " style="color: blue; font-size: 18px;"></i></a></td>
        
                                            <td class="text-center">
                                            <a href="#" onclick="copyToClipboard('<?php echo addslashes($_SERVER['HTTP_HOST'] . '/' . $row['short_code']); ?>')" title="Copy Short URL">
                                                    <i class="fas fa-copy text-primary" style=" font-size: 18px;"></i>
                                                </a>
                                    
                                            </td>
                                            <td class="text-center">
                                            
                                                <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/edit_mailto_link.php?shortcode=' . $row['short_code']);?>" title="Edit">
                                                    <i class="fas fa-edit text-warning" ></i>
                                                </a>
                                                <a class="link_crud_icons"  href="<?php echo htmlspecialchars($baseUrl . 'links/delete_shortlink.php?shortcode=' . $row['short_code']);?>" title="Delete">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    
                                    <?php
                                    }
                                    
                                }
                                
                                else if($link_type_filter=="sms_link"){
                                    $query = "SELECT * FROM sms_link where user_id = $user_id";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>

                                    <thead class="thead-light">
                                    <tr>
                                        <th>Link Name</th>
                                        <th>Short URL</th>
                                        <th>Reciever</th>
                                        <th>Message</th>
                                        <th>Details</th>
                                        <th>Analytics</th>
                                        <th>Copy</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $row['link_name']?></td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $row['short_code']); ?>" target="_blank"> <?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ."/".$row['short_code']); ?></a></td>
                                            <td class="text-center"><?php echo $row['reciever']?></td>
                                            <td class="text-center"><?php echo $row['message']?></td>

                                            <td class="text-center"> <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?shortcode=' . $row['short_code']); ?>"  title="View">
                                                View
                                                </a>
                                            </td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'index.php?shortcode=' . $row['short_code']); ?>" style="text-center"> <i class="fas fa-bar-chart " style="color: blue; font-size: 18px;"></i></a></td>
        
                                            <td class="text-center">
                                            <a href="#" onclick="copyToClipboard('<?php echo addslashes($_SERVER['HTTP_HOST'] . '/' . $row['short_code']); ?>')" title="Copy Short URL">
                                                    <i class="fas fa-copy text-primary" style="font-size: 18px;"></i>
                                                </a>
                                    
                                            </td>
                                            <td class="text-center">
                                            
                                                <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/edit_sms_link.php?shortcode=' . $row['short_code']);?>" title="Edit">
                                                    <i class="fas fa-edit text-warning" ></i>
                                                </a>
                                                <a class="link_crud_icons"  href="<?php echo htmlspecialchars($baseUrl . 'links/delete_shortlink.php?shortcode=' . $row['short_code']);?>" title="Delete">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    
                                    <?php
                                    }
                                    
                                }
                                else if($link_type_filter=="tel_link"){
                                    $query = "SELECT * FROM tel_link where user_id = $user_id";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>

                                    <thead class="thead-light">
                                    <tr>
                                        <th>Link Name</th>
                                        <th>Short URL</th>
                                        <th>Reciever</th>
                                        <th>Details</th>
                                        <th>Analytics</th>
                                        <th>Copy</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $row['link_name']?></td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $row['short_code']); ?>" target="_blank"> <?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ."/".$row['short_code']); ?></a></td>
                                            <td class="text-center"><?php echo $row['reciever']?></td>

                                            <td class="text-center"> <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?shortcode=' . $row['short_code']); ?>"  title="View">
                                                View
                                                </a>
                                            </td>
                                            <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'index.php?shortcode=' . $row['short_code']); ?>" style="text-center"> <i class="fas fa-bar-chart " style="color: blue; font-size: 18px;"></i></a></td>
        
                                            <td class="text-center">
                                            <a href="#" onclick="copyToClipboard('<?php echo addslashes($_SERVER['HTTP_HOST'] . '/' . $row['short_code']); ?>')" title="Copy Short URL">
                                                    <i class="fas fa-copy text-primary" style="font-size: 18px;"></i>
                                                </a>
                                    
                                            </td>
                                            <td class="text-center">
                                            
                                                <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/edit_tel_link.php?shortcode=' . $row['short_code']);?>" title="Edit">
                                                    <i class="fas fa-edit text-warning" ></i>
                                                </a>
                                                <a class="link_crud_icons"  href="<?php echo htmlspecialchars($baseUrl . 'links/delete_shortlink.php?shortcode=' . $row['short_code']);?>" title="Delete">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    
                                    <?php
                                    }
                                    
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
