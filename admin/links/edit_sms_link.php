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
$sms_link_updated = false;
$message = "Tel Short Link Updated Succesfully";

// Fetch the short link details from the database based on the given ID
if (isset($_GET['shortcode'])) {
    $sms_short_code = $_GET['shortcode'];
    $query = "SELECT * FROM sms_link WHERE short_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $sms_short_code);
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
  $link_name = htmlspecialchars(trim($_POST['linkname']));
  $reciever = htmlspecialchars(trim($_POST['phoneNumber']));
  $sms_body = htmlspecialchars(trim($_POST['message']));


    // Update the record in the database
    $stmt = $conn->prepare(
        "UPDATE sms_link SET
            user_id = ?, link_name = ?, short_code= ?, reciever = ?, message = ?
         WHERE short_code = ?"
    );

    $stmt->bind_param(
        'isssss',
        $user_id,
        $link_name,
        $sms_short_code,
        $reciever,
        $sms_body,
        $sms_short_code
    );

    if ($stmt->execute()) {
        $sms_link_updated = true;
        echo "Short link updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
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
            <div class="card_title bg-primary text-white  mt-3">
                        <h3 >Edit SMS Link</h3>
                    </div>
                <div class="container dashboard_card">
                <?php if($sms_link_updated):?>
                   <div class="show_link my-4" >
                
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Link updated successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                   
                         <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Link Name</th>
                                    <th>Short URL</th>
                                    <th>To (Phone Number)</th>
                                    <th>Message</th>
                                    <th>Details</th>
                                    <th>Copy</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <!-- Example Row (Repeat for each link in your database) -->
                        
                                    <tr>
                                    <td class="text-center"><?php echo $link_name?></td>
                                    <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $sms_short_code); ?>" target="_blank"><?php echo $_SERVER['HTTP_HOST']?>/<?php echo $link_data['short_code'] ?></a></td>
                                    <td class="text-center"><?php echo $reciever?></td>
                                    <td class="text-center"><?php echo $sms_body?></td>
                                    
                                    <td class="text-center"> <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?shortcode=' . $sms_short_code); ?>"  title="View">
                                           View
                                        </a>
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
                <form  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?shortcode=" . $sms_short_code; ?>" method="POST" class="mt-4">
                <div class="form-group">
                            <label for="LinkName">Link Name</label>
                            <input type="text" id="LinkName" value="<?php echo $link_data['link_name']?>" name="linkname" class="form-control" placeholder="Enter Link Name" required>
                        </div>     
                <div class="form-group">
                        <label for="phoneNumber">To (Phone Number)</label>
                        <input type="tel" id="phoneNumber" name="phoneNumber" value="<?php echo $link_data['reciever']?>" class="form-control" placeholder="+1234567890" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="form-control" rows="3" maxlength="160" placeholder="Enter message text (max 160 characters)"><?php echo $link_data['message']?></textarea>
                        <small class="form-text text-muted">Note: Messages over 160 characters may be split into multiple messages.</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Generate Short SMS Link</button>
                </form>

                <hr class="my-4">
                
                <div id="result" class="mt-4">
                    <!-- Display shortened link here after form submission -->
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
