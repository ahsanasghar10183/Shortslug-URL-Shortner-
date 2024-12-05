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

if (isset($_GET['shortcode'])) {
    $shortcode = $_GET['shortcode'];
    $short_link_query = "SELECT link_type FROM urls WHERE short_code = ?";
    $short_link_stmt = $conn->prepare($short_link_query);
    $short_link_stmt->bind_param('s', $shortcode);
    $short_link_stmt->execute();
    $shortlink = $short_link_stmt->get_result();


    // querying the email links table
    $mailto_query = "SELECT link_type FROM email_link WHERE short_code = ?";
    $mailto_stmt = $conn->prepare($mailto_query);
    $mailto_stmt->bind_param('s', $shortcode);
    $mailto_stmt->execute();
    $mailto_link = $mailto_stmt->get_result();
    // querying the sms link table
    $sms_query = "SELECT link_type FROM sms_link WHERE short_code = ?";
    $sms_stmt = $conn->prepare($sms_query);
    $sms_stmt->bind_param('s', $shortcode);
    $sms_stmt->execute();
    $sms_short_link = $sms_stmt->get_result();
//    querying the tel link table
    $tel_query = "SELECT link_type FROM tel_link WHERE short_code = ?";
    $tel_stmt = $conn->prepare($tel_query);
    $tel_stmt->bind_param('s', $shortcode);
    $tel_stmt->execute();
    $tel_shortlink = $tel_stmt->get_result();

    if ($shortlink->num_rows > 0) {
        $rows = $shortlink->fetch_assoc();
        $link_type_filter = $rows['link_type'];
    }
    else if ($mailto_link->num_rows > 0) {
        $rows = $mailto_link->fetch_assoc();
        $link_type_filter = $rows['link_type'];
    }
    else if ($sms_short_link->num_rows > 0) {
        $rows = $sms_short_link->fetch_assoc();
        $link_type_filter = $rows['link_type'];
    }
    else if ($tel_shortlink->num_rows > 0) {
        $rows = $tel_shortlink->fetch_assoc();
        $link_type_filter = $rows['link_type'];
    }
    $short_link_stmt->close();
    $mailto_stmt->close();
    $sms_stmt->close();
    $tel_stmt->close();



} else {
    echo "No ID provided.";
    exit;
}




 ?>






<body class="with-welcome-text">
    <!-- Navbar -->
    <?php include($baseurl . '/includes/header.php'); ?>
    <!-- Sidebar -->
    <div class="container-fluid page-body-wrapper">
        <?php include($baseurl . '/includes/sidebar.php'); ?>

        <!-- Main Panel -->
                <div class="main-panel">
                    <div class="content-wrapper container">
                        <!-- Title Card -->
                        <div class="row">
                            <div class="card_title bg-primary text-white  mt-3">
                                <h3 >Links Details</h3>
                            </div>
                            <div class="container dashboard_card">
                                 <!-- Dashboard Card -->
              
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="analytics_page.php" class="btn btn-outline-primary d-flex align-items-center border-primary">
                            <i class="fa-solid fa-chart-line me-2"></i> View Analytics
                        </a>
                        <div>
                            <!-- Action Buttons -->
                            <a href="<?php echo $baseUrl?>links/edit_short_url.php" class="details_edit_icon me-2"><i class="fas fa-edit"></i> <span>Edit</span> </a>
                            <a href="#" class="details_delete_icon  ms-2" onclick="deleteLink()"><i class="fas fa-trash"></i> <span>Delete</span> </a>
                        </div>
                    </div>

                    <!-- Link Information Section -->
                    <div class="row">
                        <!-- Left Column: Link Details -->
                        <div class="col-md-8">
                            <table class="table table-borderless">


                            <?php
                              if($link_type_filter=="shortlink"){
                                // Use prepared statement to fetch URL details
                                $query = "SELECT * FROM urls WHERE short_code = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param('s', $shortcode);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $url = $result->fetch_assoc();
                                $linkStatus = ($url['view_count'] >= 2) ? "Expired" : ($url['status'] ? "Active" : "Inactive");

                                $cloaking_status= $url['is_cloaking']? "Enabled" : "Disabled";
                                $oneTimeLink = $url['one_time_view']? "Yes" : "No"
                                ?>
                                <tbody>
                                    <tr>
                                        <th >Link Destination</th>
                                        <td><?php echo $url['destination_url']?></td>
                                    </tr>
                                    <tr>
                                        <th >Name</th>
                                        <td><?php echo $url['link_name']?></td>
                                    </tr>
                                    <tr>
                                        <th >Link Status</th>
                                        <td><span class="badge badge-success"><?php echo $linkStatus?></span></td>
                                    </tr>
                                    <tr>
                                        <th >Link Cloaking</th>
                                        <td><?php echo $cloaking_status?></td>
                                    </tr>
                                    <tr>
                                        <th >Social Sharing</th>
                                        <td>
                                            <strong >Title:</strong> <?php echo $url['social_title']?><br>
                                            <strong >Description:</strong> <?php echo $url['social_description']?><br>
                                            <strong >Image:</strong> 
                                            <img src="<?php echo $baseUrl; ?>/assets/images/short-slug-logo.png" alt="Social Image" class="details_social_preview_image">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th >Expiry Date & Time</th>
                                        <td><?php $url['expiry_date'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Link Destination After Expiry</th>
                                        <td><?php $url['destination_after_expiry'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Link Password</th>
                                        <td><?php echo $url['link_password']?></td>
                                    </tr>
                                    <tr>
                                        <th>One-Time View Link</th>
                                        <td><?php echo $oneTimeLink?></td>
                                    </tr>
                                </tbody>
                             <?php
                                }
                              else if($link_type_filter=="mailto_link"){
                                // Use prepared statement to fetch URL details
                                $query = "SELECT * FROM email_link WHERE short_code = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param('s', $shortcode);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $email_link = $result->fetch_assoc();
                                ?>
                                <tbody>
                                   
                                    <tr>
                                        <th >Link Name</th>
                                        <td><?php echo $email_link['link_name']?></td>
                                    </tr>
                                    <tr>
                                        <th >To (Email Address)</th>
                                        <td><?php echo $email_link['reciever']?></td>
                                    </tr>
                                    <tr>
                                        <th >Subject</th>
                                        <td><?php echo $email_link['subject']?></td>
                                    </tr>
                                    <tr>
                                        <th >Email Body</th>
                                        <td><?php echo $email_link['body']?></td>
                                    </tr>
                                   
                                </tbody>
                             <?php
                                }

                                else if($link_type_filter=="sms_link"){
                                    // Use prepared statement to fetch URL details
                                    $query = "SELECT * FROM sms_link WHERE short_code = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param('s', $shortcode);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $sms_link = $result->fetch_assoc();
                                    ?>
                                    <tbody>
                                       
                                        <tr>
                                            <th >Link Name</th>
                                            <td><?php echo $sms_link['link_name']?></td>
                                        </tr>
                                        <tr>
                                            <th >To (Phone Number)</th>
                                            <td><?php echo $sms_link['reciever']?></td>
                                        </tr>
                                        <tr>
                                            <th >Message</th>
                                            <td><?php echo $sms_link['message']?></td>
                                        </tr>
            
                                       
                                    </tbody>
                                 <?php
                                    }

                                    else if($link_type_filter=="tel_link"){
                                        // Use prepared statement to fetch URL details
                                        $query = "SELECT * FROM tel_link WHERE short_code = ?";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param('s', $shortcode);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $tel_link = $result->fetch_assoc();
                                        ?>
                                        <tbody>
                                           
                                            <tr>
                                                <th >Link Name</th>
                                                <td><?php echo $tel_link['link_name']?></td>
                                            </tr>
                                            <tr>
                                                <th >To (Phone Number)</th>
                                                <td><?php echo $tel_link['reciever']?></td>
                                            </tr>
                
                                           
                                        </tbody>
                                     <?php
                                        }
                              ?>
                                
                            </table>
                        </div>

                        <!-- Right Column: QR Code -->
                        <div class="col-md-3 text-center">
                            <div class="p-4 border rounded shadow-sm">
                            <img src="<?php echo $baseUrl . 'assets/images/qrcodes/' . htmlspecialchars($shortcode) . '.png'; ?>" style="width: 150px; height: 150px; " alt="QR Code">
   
                           
                            </div>
                            <br>
                            <button type="download" class="btn btn-primary w-100">Download Qr Code</button>
                        </div>


                        </div>
                           
                    
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer mt-4">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                        Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.
                    </span>
                    <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">
                        Copyright © 2023. All rights reserved.
                    </span>
                </div>
            </footer>
        </div>
    </div>

    <?php include($baseurl . '/includes/postjs.php'); ?>
</body>
