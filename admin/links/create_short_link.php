<?php
$baseurl = dirname(__DIR__, 1);
include($baseurl . '/includes/head.php');
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
                        <form action="path/to/bulk_upload_handler.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="file" class="form-control-file" name="csvFile" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Upload CSV</button>
                        </form>
                   <hr>
                        <form>
                            <!-- Link Destination -->
                            <div class="form-group">
                                <label for="linkDestination" class="text-dark">Link Destination</label>
                                <input type="url" class="form-control" id="linkDestination" placeholder="Enter destination URL" required>
                            </div>

                            <!-- Link Name -->
                            <div class="form-group">
                                <label for="linkName" class="text-dark">Link Name</label>
                                <input type="text" class="form-control" id="linkName" placeholder="Enter link name" required>
                            </div>

                            <!-- Link Status -->
                            <div class="form-group">
                                <label class="text-dark" for="linkStatus">Link Status</label><br>
                                <label class="custom-toggle">
                                    <input type="checkbox" id="linkStatus" name="linkStatus">
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
                                            <select class="form-control" id="countryTarget">
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
                                            <input type="url" class="form-control" id="countryDestination" placeholder="Enter destination URL">
                                        </div>
                                    </div>

                                    <!-- Device Tab -->
                                    <div class="tab-pane fade" id="device" role="tabpanel" aria-labelledby="device-tab">
                                        <div class="form-group mt-3">
                                            <label for="deviceType" class="text-dark">Select Device Type</label>
                                            <select class="form-control" id="deviceType">
                                                <option>Select Device Type</option>
                                                <option>Desktop</option>
                                                <option>Mobile</option>
                                                <option>Tablet</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="deviceDestination" class="text-dark">Destination URL</label>
                                            <input type="url" class="form-control" id="deviceDestination" placeholder="Enter destination URL">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Sharing -->
                            <div class="form-group">
                                <label class="text-dark">Social Sharing</label>
                                <input type="text" class="form-control mb-2" id="socialTitle" placeholder="Title">
                                <input type="text" class="form-control mb-2" id="socialDescription" placeholder="Description">
                                <input type="file" class="form-control-file" id="socialImage">
                            </div>

                            <!-- Expiry Date & Time -->
                            <div class="form-group">
                                <label for="expiryDate" class="text-dark">Expiry Date & Time</label>
                                <input type="datetime-local" class="form-control" id="expiryDate">
                            </div>

                            <!-- Link Destination After Expiry -->
                            <div class="form-group">
                                <label for="linkAfterExpiry" class="text-dark">Link Destination After Expiry</label>
                                <input type="url" class="form-control" id="linkAfterExpiry" placeholder="Enter destination after expiry">
                            </div>

                            <!-- Link Password -->
                            <div class="form-group">
                                <label for="linkPassword" class="text-dark">Link Password</label>
                                <input type="password" class="form-control" id="linkPassword" placeholder="Enter password (optional)">
                            </div>

                            <!-- One-time View Link -->
                            <div class="form-group mb-3">
                                <input type="checkbox" class="form-check-input" style="height:16px; width: 16px;" id="oneTimeView">
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
