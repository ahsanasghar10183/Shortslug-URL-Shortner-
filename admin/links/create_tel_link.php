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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link_name = $_POST['linkname'];
    $reciever = $_POST['phoneNumber'];
    // Generate unique short code
    $short_code = substr(md5(uniqid(mt_rand(), true)), 0, 6);

    $stmt = $conn->prepare(
        "INSERT INTO tel_link (
            user_id,
            link_name, short_code, reciever
        ) VALUES (?, ?, ?, ?)"
    );
    
    // Bind parameters
    $stmt->bind_param(
        'isss', 
        $user_id,
        $link_name, 
        $short_code, 
        $reciever
        );
    


    if ($stmt->execute()) {
     
        $short_link_generated = True;
    } else {
        echo "Error: " . $stmt->error;
    }
    // $fetch_id = 'SELECT id from urls WHERE ID 

    $url = "https://www.apple.com/" . $short_code; // Replace with your domain
    $path = ($baseurl. '/assets/images/qrcodes/');

    $qrcode =$path. $short_code ."png";
    // Generate the QR Code
    QRcode::png($url, $qrcode, QR_ECLEVEL_L, 10);

    echo "QR Code generated: <img src='$qrcode' />";

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
                        <h3 >Generate Telephone Short Link</h3>
                    </div>
                <div class="container dashboard_card">
                <?php if($short_link_generated):?>
                   <div class="show_link my-4" >
                
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Link Created successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                   
                         <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Link Name</th>
                                    <th>Short URL</th>
                                    <th>To (Phone Number)</th>
                                  
                                    <th>Details</th>
                                    <th>Copy</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <!-- Example Row (Repeat for each link in your database) -->
                        
                                    <tr>
                                    <td class="text-center"><?php echo $link_name?></td>
                                    <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $tel_short_code); ?>" target="_blank"><?php echo $_SERVER['HTTP_HOST']?>/<?php echo $short_code ?></a></td>
                                    <td class="text-center"><?php echo $reciever?></td>
                                    <td class="text-center"> <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?shortcode=' . $tel_short_code); ?>"  title="View">
                                           View
                                        </a>
                                     </td>
                                    <td class="text-center">
                                    <a href="#" onclick="copyToClipboard('<?php echo addslashes($_SERVER['HTTP_HOST'] . '/' . $short_code); ?>')" title="Copy Short URL">
                                              <i class="fas fa-copy text-primary" style="font-size: 18px;"></i>
                                          </a>
                              
                                    </td>
                                </tr>
                              
                                <!-- Repeat rows for each link -->
                            </tbody>
                        </table>
                   </div>
                   <?php endif ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="mt-4">
                <div class="form-group">
                            <label for="LinkName">Link Name</label>
                            <input type="text" id="LinkName" name="linkname" class="form-control" placeholder="Enter Link Name" required>
                        </div>   
                <div class="form-group">
                        <label for="phoneNumber">Phone Number</label>
                        <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="+1234567890" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Generate Short Tel Link</button>
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
