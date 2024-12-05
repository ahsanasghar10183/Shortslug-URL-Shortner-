<?php
$baseurl = dirname(__DIR__, 1);
$vendorpath = dirname(__DIR__, 2);

include($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');
require_once($vendorpath . '/vendor/phpqrcode/phpqrcode.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}
$user_id = $_SESSION['user_id'];
$short_link_updated = false;

// Fetch the short link details from the database based on the given ID
if (isset($_GET['shortcode'])) {
    $link_short_code = $_GET['shortcode'];
    $query = "SELECT * FROM urls WHERE short_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $link_short_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $link_data = $result->fetch_assoc();
    } else {
        echo "Invalid ID or no link found.";
        exit;
    }
} else {
    echo "No ID provided.";
    exit;
}

// Handle the update operation when the form is submitted
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

    // Targeting
    $country_target = isset($_POST['countryTarget']) ? json_encode($_POST['countryTarget']) : null;
    $country_destination = $_POST['countryDestination'] ?? null;
    $device_target = isset($_POST['deviceTarget']) ? json_encode($_POST['deviceTarget']) : null;
    $device_destination = $_POST['deviceDestination'] ?? null;

    // Update the record in the database
    $stmt = $conn->prepare(
        "UPDATE urls SET
            user_id =?, destination_url = ?, link_name = ?, status = ?, is_cloaking = ?, cloaking_alias = ?, 
            social_title = ?, social_description = ?, expiry_date = ?, 
            destination_after_expiry = ?, link_password = ?, one_time_view = ?, 
            country_target = ?, country_based_destination_link = ?, 
            device_target = ?, device_based_destination_link = ?
         WHERE short_code = ?"
    );

    $stmt->bind_param(
        'ississsssssssssss',
        $user_id,
        $destination_url,
        $link_name,
        $status,
        $is_cloaking,
        $cloaking_alias,
        $social_title,
        $social_description,
        $expiry_date,
        $destination_after_expiry,
        $link_password,
        $one_time_view,
        $country_target,
        $country_destination,
        $device_target,
        $device_destination,
        $link_short_code
    );

    if ($stmt->execute()) {
        $short_link_updated = true;
        echo "Short link updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<body class="with-welcome-text">
    <?php include($baseurl . '/includes/header.php') ?>
    <div class="container-fluid page-body-wrapper">
        <?php include($baseurl . '/includes/sidebar.php') ?>
        <div class="main-panel">
            <div class="content-wrapper">
            <div class="row">
                    <div class="card_title bg-primary text-white mt-3">
                        <h3>Edit Short URL</h3>
                    </div>

                    <!-- Bulk Upload Section -->
                    <div class="container dashboard_card mb-4">
                  
                   <?php if($short_link_updated):?>
                   
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Link updated successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                   <div class="show_link my-4" >
                         <table class="table table-hover">
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
                                    <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $link_short_code); ?>" target="_blank"><?php echo $_SERVER['HTTP_HOST']?>/<?php echo $link_data['short_code'] ?></a></td>
                                    <td class="text-center"><?php echo $destination_url?></td>
                                    <td class="text-center"><?php echo $expiry_date?></td>
                                    <td class="text-center"><?php echo $expiry_date?></td>
                                    <td class="text-center"> <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?id=' . $link_id); ?>"  title="View">
                                           View
                                        </a>
                                     </td>
                                    <td class="text-center text-sm"><span class="badge <?php echo $link_data['view_count'] >= 1 ? 'badge-danger' : ($link_data['status'] ? 'badge-success' : 'badge-secondary'); ?>">
                                    <?php echo $link_data['view_count'] >= 1 ? "Expired" : ($link_data['status'] ? "Active" : "Inactive"); ?></span>
                                  </td>
                                    <td class="text-center">
                                    <a href="#" onclick="copyToClipboard('<?php echo addslashes($_SERVER['HTTP_HOST'] . '/' . $link_data['short_code']); ?>')" title="Copy Short URL">
                                              <i class="fas fa-copy text-primary" style="font-size: 18px;"></i>
                                          </a>
                              
                                    </td>
                                </tr>
                              
                                <!-- Repeat rows for each link -->
                            </tbody>
                        </table>
                   </div>
                   <?php endif ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?shortcode=" . $link_short_code; ?>" method="post">
                        
                            <!-- Link Destination -->
                            <div class="form-group">
                                <label for="linkDestination" class="text-dark">Link Destination</label>
                                <input type="url" class="form-control" name="linkDestination" id="linkDestination" value="<?php echo $link_data['destination_url']?>" placeholder="Enter destination URL" required>
                            </div>

                            <!-- Link Name -->
                            <div class="form-group">
                                <label for="linkName" class="text-dark">Link Name</label>
                                <input type="text" class="form-control" name="linkName" id="linkName"  value="<?php echo $link_data['link_name']?>" placeholder="Enter link name" required>
                            </div>

                            <!-- Link Status -->
                            <div class="form-group">
                                <label class="text-dark" for="linkStatus">Link Status</label><br>
                                <label class="custom-toggle">
                                    <input type="checkbox" name="linkStatus" id="linkStatus" <?php echo $link_data['status']? 'checked': '' ?> name="linkStatus">
                                    <span></span>
                                </label>
                            </div>

                            <!-- Is Cloaking -->
                            <div class="form-group">
                                <label class="text-dark" for="enable_cloaking">Link Cloaking</label><br>
                                <label class="custom-toggle">
                                    <input type="checkbox" id="link_cloaking_Status"  <?php echo $link_data['is_cloaking']? 'checked':'' ?> name="link_cloaking_Status">
                                    <span></span>
                                </label>
                               <div id="cloaking_aliasContainer" style="display: none;">
                                  <label for="linkName" class="text-dark">Alias</label>
                                <input type="text" class="form-control" name="cloaking_alias" id="cloaking_alias"  value="<?php echo $link_data['cloaking_alias']?>" placeholder="Enter alias">
                         
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
                                            <select class="form-control" name="countryTarget"  selected="<?php echo $link_data['country_target']?>" id="countryTarget">
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
                                            <input type="url" class="form-control" name ="countryDestination" id="countryDestination" value="<?php echo $link_data['device_based_destination_link']?>"  placeholder="Enter destination URL">
                                        </div>
                                    </div>

                                    <!-- Device Tab -->
                                    <div class="tab-pane fade" id="device" role="tabpanel" aria-labelledby="device-tab">
                                        <div class="form-group mt-3">
                                            <label for="deviceType" class="text-dark">Select Device Type</label>
                                            <select class="form-control" name="deviceType"  id="deviceType">
                                              <option value="">Select Device Type</option>
                                              <option value="Desktop" <?php echo ($link_data['device_target'] === 'Desktop') ? 'selected' : ''; ?>>Desktop</option>
                                              <option value="Mobile" <?php echo ($link_data['device_target'] === 'Mobile') ? 'selected' : ''; ?>>Mobile</option>
                                              <option value="Tablet" <?php echo ($link_data['device_target'] === 'Tablet') ? 'selected' : ''; ?>>Tablet</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="deviceDestination" class="text-dark">Destination URL</label>
                                            <input type="url" class="form-control" name="deviceDestination" value="<?php echo $link_data['device_based_destination_link']?>" id="deviceDestination" placeholder="Enter destination URL">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Sharing -->
                            <div class="form-group">
                                <label class="text-dark">Social Sharing</label>
                                <input type="text" class="form-control mb-2" name="socialTitle" id="socialTitle" value="<?php echo ($link_data['social_title']); ?>" placeholder="Title">
                                <input type="text" class="form-control mb-2" name="socialDescription" id="socialDescription" value="<?php echo ($link_data['social_description']); ?>" placeholder="Description">
                                <input type="file" class="form-control-file" name="socialImage" id="socialImage">
                            </div>

                            <!-- Expiry Date & Time -->
                            <div class="form-group">
                                <label for="expiryDate" class="text-dark">Expiry Date & Time</label>
                                <input type="datetime-local" class="form-control" name="expiryDate" value="<?php echo htmlspecialchars($link_data['expiry_date']); ?>" id="expiryDate">
                            </div>

                            <!-- Link Destination After Expiry -->
                            <div class="form-group">
                                <label for="linkAfterExpiry" class="text-dark">Link Destination After Expiry</label>
                                <input type="url" class="form-control" name="linkAfterExpiry" id="linkAfterExpiry" value="<?php echo htmlspecialchars($link_data['destination_after_expiry']); ?>" placeholder="Enter destination after expiry">
                            </div>

                            <!-- Link Password -->
                            <div class="form-group">
                                <label for="linkPassword" class="text-dark">Link Password</label>
                                <input type="password" class="form-control" name="linkPassword" id="linkPassword" value="<?php echo $link_data['link_password'] ?>" placeholder="Enter password (optional)">
                            </div>

                            <!-- One-time View Link -->
                            <div class="form-group mb-3">
                                <input type="checkbox" class="form-check-input" style="height:16px; width: 16px;" name="oneTimeView" id="oneTimeView" <?php echo $link_data['one_time_view']? "checked": " " ?>>
                                <label class="form-check-label text-dark ps-3" style="font-size: 14px;" for="oneTimeView">One-time View Link</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-block">Update Link</button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
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