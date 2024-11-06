
<?php include('./includes/head.php') ?>

<body class="with-welcome-text">

    <!-- Navbar here -->
    <?php include('./includes/header.php') ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!----Sidebar Injecting here--->
        <?php include('./includes/sidebar.php') ?>
        <div class="container mt-5">
            <h2 class="text-primary mb-4">Generate Short URL & QR Code</h2>
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
                    <label class="text-primary">Link Status</label><br>
                    <button type="button" class="btn btn-primary mr-2" id="enableStatus">Enable</button>
                    <button type="button" class="btn btn-secondary" id="disableStatus">Disable</button>
                </div>

                <!-- Is Cloaking -->
                <div class="form-group">
                    <label class="text-primary">Cloaking</label><br>
                    <button type="button" class="btn btn-primary mr-2" id="enableCloaking">Enable</button>
                    <button type="button" class="btn btn-secondary" id="disableCloaking">Disable</button>
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

                <!-- One-time View Link -->
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="oneTimeView">
                    <label class="form-check-label text-primary" for="oneTimeView">One-time View Link</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block">Generate Link</button>
            </form>
        </div>
    </div>
    <?php include('./includes/postjs.php')?>

