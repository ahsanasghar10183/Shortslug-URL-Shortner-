<?php
$baseurl = dirname(__DIR__, 1);
include($baseurl . '/includes/head.php');
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
                                <tbody>
                                    <tr>
                                        <th >Link Destination</th>
                                        <td>https://example.com/destination-url</td>
                                    </tr>
                                    <tr>
                                        <th >Name</th>
                                        <td>Sample Link Name</td>
                                    </tr>
                                    <tr>
                                        <th >Link Status</th>
                                        <td><span class="badge badge-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <th >Link Cloaking</th>
                                        <td>Enabled</td>
                                    </tr>
                                    <tr>
                                        <th >Social Sharing</th>
                                        <td>
                                            <strong >Title:</strong> Social Title Example<br>
                                            <strong >Description:</strong> This is a sample description for social sharing.<br>
                                            <strong >Image:</strong> 
                                            <img src="<?php echo $baseUrl; ?>/assets/images/short-slug-logo.png" alt="Social Image" class="details_social_preview_image">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th >Expiry Date & Time</th>
                                        <td>2023-06-01 12:00 PM</td>
                                    </tr>
                                    <tr>
                                        <th>Link Destination After Expiry</th>
                                        <td>https://example.com/after-expiry</td>
                                    </tr>
                                    <tr>
                                        <th>Link Password</th>
                                        <td>Protected</td>
                                    </tr>
                                    <tr>
                                        <th>One-Time View Link</th>
                                        <td>Yes</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Right Column: QR Code -->
                        <div class="col-md-3 text-center">
                            <div class="p-4 border rounded shadow-sm">
                                <img src="<?php echo $baseUrl ?>assets/images/qr_code_sample.png" alt="QR Code"  class="qr_image">
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
