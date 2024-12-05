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
                        <h3>Generate Short URL & QR Code Through Bulk Upload</h3>
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