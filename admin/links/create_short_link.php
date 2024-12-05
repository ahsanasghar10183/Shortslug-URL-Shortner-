<?php
$baseurl = dirname(__DIR__, 1);
$vendorpath = dirname(__DIR__, 2);

include($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');
require_once($vendorpath. '/vendor/phpqrcode/phpqrcode.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}
$user_id = $_SESSION['user_id'];

// include 'phpqrcode/qrlib.php'; 
$short_link_generated =false;
$link_status = "Inactive";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination_url = $_POST['linkDestination'];
    $link_name = $_POST['linkName'];
    $status = isset($_POST['linkStatus']) ? 1 : 0;
    $is_cloaking = isset($_POST['link_cloaking_Status']) ? 1 : 0;
    $cloaking_alias = $_POST['cloaking_alias'] ?? null;
    $expiry_date = !empty($_POST['expiryDate']) ? $_POST['expiryDate'] : null;
    $destination_after_expiry = $_POST['linkAfterExpiry'] ?? null;
    $link_password = $_POST['linkPassword'] ?? null;
    $one_time_view = isset($_POST['oneTimeView']) ? 1 : 0;

    // Social sharing data
    $social_title = $_POST['socialTitle'] ?? null;
    $social_description = $_POST['socialDescription'] ?? null;
    $social_image_path = ''; // Placeholder for image upload
  
    // Targeting
    $country_target = isset($_POST['countryTarget']) ? json_encode($_POST['countryTarget']) : null;
    $countryDestination = $_POST['countryDestination'] ?? null;
    $device_target = isset($_POST['deviceTarget']) ? json_encode($_POST['deviceTarget']) : null;
    $deviceDestination = $_POST['deviceDestination'] ?? null;

    // Generate unique short code
    $short_code = substr(md5(uniqid(mt_rand(), true)), 0, 6);

    $stmt = $conn->prepare(
        "INSERT INTO urls (
            user_id,
            destination_url, short_code, link_name, status, is_cloaking, cloaking_alias, 
            social_title, social_description, social_image_path, expiry_date, 
            destination_after_expiry, link_password, one_time_view, 
            country_target, country_based_destination_link, device_target, device_based_destination_link
        ) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    
    // Bind parameters
    $stmt->bind_param(
        'isssssssssssssssss', 
        $user_id,
        $destination_url, 
        $short_code, 
        $link_name, 
        $status, 
        $is_cloaking, 
        $cloaking_alias, 
        $social_title, 
        $social_description, 
        $social_image_path, 
        $expiry_date, 
        $destination_after_expiry, 
        $link_password, 
        $one_time_view, 
        $country_target, 
        $countryDestination, 
        $device_target, 
        $deviceDestination
    );
    


    if ($stmt->execute()) {
     
        $short_link_generated = True;
        $link_status = ($status)? "Active" : "Inactive";
    } else {
        echo "Error: " . $stmt->error;
    }
    // $fetch_id = 'SELECT id from urls WHERE ID 

    $url = "" . $short_code; // Replace with your domain
    $path = ($baseurl. '/assets/images/qrcodes/');

    $qrcode =$path. $short_code .".png";
    // Generate the QR Code
    QRcode::png($url, $qrcode, QR_ECLEVEL_L, 10);

    echo "QR Code generated: <img src='$qrcode' />";

    $stmt->close();
}


// Handling Qr Code functionality Here






?>

<body class="with-welcome-text">
    <!-- Navbar here -->
    <?php include($baseurl . '/includes/header.php') ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- Sidebar Injecting here -->
        <?php include($baseurl . '/includes/sidebar.php') ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="card_title bg-primary text-white mt-3">
                        <h3>Generate Short URL & QR Code</h3>
                    </div>

                    <!-- Bulk Upload Section -->
                    <div class="container dashboard_card mb-4">
                        <h5 class="text-dark">Bulk Upload CSV</h5>
                        <form action="<?php echo htmlspecialchars($baseUrl) . 'links/handle_Bulk_upload.php'; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="file" class="form-control-file" name="csvFile" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Upload CSV</button>
                        </form>
                   <hr>
                   <?php if($short_link_generated):?>
                   <div class="show_link my-4" >
                         <table class="table table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Link Name</th>
                                    <th>Short URL</th>
                                    <th>Destination</th>
                                    <th>Creation Date</th>
                                    <th>Expiry Date</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Copy</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <!-- Example Row (Repeat for each link in your database) -->
                        
                                    <tr>
                                    <td class="text-center"><?php echo $link_name?></td>
                                    <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/url_redirect.php?shortcode=' . $short_code); ?>" target="_blank"><?php echo $_SERVER['HTTP_HOST']?>/<?php echo $short_code ?></a></td>
                                    <td class="text-center"><?php echo $destination_url?></td>
                                    <td class="text-center"><?php echo $expiry_date?></td>
                                    <td class="text-center"><?php echo $expiry_date?></td>
                                    <td class="text-center"> <a class="link_crud_icons"  href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?shortcode=' . $row['short_code']); ?>"    title="View">
                                           View
                                        </a>
                                     </td>
                                    <td class="text-center text-sm"><span class="badge badge-success"><?php echo $link_status?></span></td>
                                    <td class="text-center">
                                       
                                    <a href="#" onclick="copyToClipboard('<?php echo $_SERVER['HTTP_HOST']?>/<?php echo $short_code ?>')" title="Copy Short URL">
                                       <i class="fas fa-copy text-primary" style="font-size: 18px;"></i>
                                     </a>
                                       
                                    </td>
                                </tr>
                              
                                <!-- Repeat rows for each link -->
                            </tbody>
                        </table>
                   </div>
                   <?php endif ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        
                            <!-- Link Destination -->
                            <div class="form-group">
                                <label for="linkDestination" class="text-dark">Link Destination</label>
                                <input type="url" class="form-control" name="linkDestination" id="linkDestination" placeholder="Enter destination URL" required>
                            </div>

                            <!-- Link Name -->
                            <div class="form-group">
                                <label for="linkName" class="text-dark">Link Name</label>
                                <input type="text" class="form-control" name="linkName" id="linkName" placeholder="Enter link name" required>
                            </div>

                            <!-- Link Status -->
                            <div class="form-group">
                                <label class="text-dark" for="linkStatus">Link Status</label><br>
                                <label class="custom-toggle">
                                    <input type="checkbox" name="linkStatus" id="linkStatus" name="linkStatus">
                                    <span></span>
                                </label>
                            </div>

                            <!-- Is Cloaking -->
                            <div class="form-group">
                                <label class="text-dark" for="enable_cloaking">Link Cloaking</label><br>
                                <label class="custom-toggle">
                                    <input type="checkbox" id="link_cloaking_Status" name="link_cloaking_Status">
                                    <span></span>
                                </label>
                               <div id="cloaking_aliasContainer" style="display: none;">
                                  <label for="linkName" class="text-dark">Alias</label>
                                <input type="text" class="form-control" name="cloaking_alias" id="cloaking_alias" placeholder="Enter alias">
                         
                               </div>
                            </div>

                            <!-- Targeting Section -->
                            <div class="form-group mt-4">
                                <label class="text-dark">Targeting</label>
                                <!-- Tab Navigation -->
                                <ul class="nav nav-tabs" id="targetingTabs" role="tablist" style="border: none;">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="none-tab" data-bs-toggle="tab" href="#none" role="tab" aria-controls="none" aria-selected="true">None</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="country-tab" data-bs-toggle="tab" href="#country" role="tab" aria-controls="country" aria-selected="false">Country</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="device-tab" data-bs-toggle="tab" href="#device" role="tab" aria-controls="device" aria-selected="false">Device</a>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content border p-3" id="targetingTabsContent" style="border-radius: 5px; border-top-left-radius: 0;">
                                    <!-- None Tab -->
                                    <div class="tab-pane fade show active" id="none" role="tabpanel" aria-labelledby="none-tab">
                                        <p class="text-muted">No specific targeting set.</p>
                                    </div>

                                    <!-- Country Tab -->
                                    <div class="tab-pane fade" id="country" role="tabpanel" aria-labelledby="country-tab">
                                        <div class="form-group mt-3">
                                            <label for="countryTarget" class="text-dark">Select Country</label>
                                            <select class="form-control" name="countryTarget" id="countryTarget">
                                                <option>Select Country</option>
                                                <option>United States</option>
                                                <option>Canada</option>
                                                <option>United Kingdom</option>
                                                <option>Australia</option>
                                                <option>India</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="countryDestination" class="text-dark">Destination URL</label>
                                            <input type="url" class="form-control" name ="countryDestination" id="countryDestination" placeholder="Enter destination URL">
                                        </div>
                                    </div>

                                    <!-- Device Tab -->
                                    <div class="tab-pane fade" id="device" role="tabpanel" aria-labelledby="device-tab">
                                        <div class="form-group mt-3">
                                            <label for="deviceType" class="text-dark">Select Device Type</label>
                                            <select class="form-control" name="deviceType"  id="deviceType">
                                                <option>Select Device Type</option>
                                                <option>Desktop</option>
                                                <option>Mobile</option>
                                                <option>Tablet</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="deviceDestination" class="text-dark">Destination URL</label>
                                            <input type="url" class="form-control" name="deviceDestination" id="deviceDestination" placeholder="Enter destination URL">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Sharing -->
                            <div class="form-group">
                                <label class="text-dark">Social Sharing</label>
                                <input type="text" class="form-control mb-2" name="socialTitle" id="socialTitle" placeholder="Title">
                                <input type="text" class="form-control mb-2" name="socialDescription" id="socialDescription" placeholder="Description">
                                <input type="file" class="form-control-file" name="socialImage" id="socialImage">
                            </div>

                            <!-- Expiry Date & Time -->
                            <div class="form-group">
                                <label for="expiryDate" class="text-dark">Expiry Date & Time</label>
                                <input type="datetime-local" class="form-control" name="expiryDate" id="expiryDate">
                            </div>

                            <!-- Link Destination After Expiry -->
                            <div class="form-group">
                                <label for="linkAfterExpiry" class="text-dark">Link Destination After Expiry</label>
                                <input type="url" class="form-control" name="linkAfterExpiry" id="linkAfterExpiry" placeholder="Enter destination after expiry">
                            </div>

                            <!-- Link Password -->
                            <div class="form-group">
                                <label for="linkPassword" class="text-dark">Link Password</label>
                                <input type="password" class="form-control" name="linkPassword" id="linkPassword" placeholder="Enter password (optional)">
                            </div>

                            <!-- One-time View Link -->
                            <div class="form-group mb-3">
                                <input type="checkbox" class="form-check-input" style="height:16px; width: 16px;" name="oneTimeView" id="oneTimeView">
                                <label class="form-check-label text-dark ps-3" style="font-size: 14px;" for="oneTimeView">One-time View Link</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-block">Generate Link</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- Footer -->
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
</body>
<?php include($baseurl . '/includes/postjs.php') ?>
<script>
$(document).ready(function () {
    // Function to toggle alias input visibility
    function toggleAliasInput() {
        const cloakingStatus = document.getElementById('link_cloaking_Status');
        const cloakingAliasInput = document.getElementById('cloaking_aliasContainer');

        if (cloakingStatus.checked) {
            // Show alias input when checkbox is checked
            cloakingAliasInput.style.display = 'block';
        } else {
            // Hide alias input when checkbox is unchecked
            cloakingAliasInput.style.display = 'none';
        }
    }

    // Run toggleAliasInput on page load to set initial state
    toggleAliasInput();

    // Attach the toggleAliasInput function to the checkbox change event
    $('#link_cloaking_Status').on('change', toggleAliasInput);
});


</script>