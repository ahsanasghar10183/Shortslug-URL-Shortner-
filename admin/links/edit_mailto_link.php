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
$mailto_link_updated = false;
$message = "Mailto Short Link Updated Succesfully";

// Fetch the short link details from the database based on the given ID
if (isset($_GET['shortcode'])) {
    $mailto_short_code = $_GET['shortcode'];
    $query = "SELECT * FROM email_link WHERE short_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $mailto_short_code);
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
    $link_name = $_POST['linkname'];
    $reciever = $_POST['toEmail'];
    $subject =$_POST['subject'];
    $email_body = $_POST['body'];

    // Update the record in the database
    $stmt = $conn->prepare(
        "UPDATE email_link SET
            user_id = ?, link_name = ?, short_code= ?, reciever = ?, subject = ?, body = ?
         WHERE short_code = ?"
    );

    $stmt->bind_param(
        'issssss',
        $user_id,
        $link_name,
        $mailto_short_code,
        $reciever,
        $subject,
        $email_body,
        $mailto_short_code
    );

    if ($stmt->execute()) {
        $mailto_link_updated = true;
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
                <div class="container mt-3 dashboard_card">
                    <h2 class="text-left ">Shorten & Track Mailto Links</h2>
                    <?php if($mailto_link_updated):?>
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
                                    <th>To (Email Address)</th>
                                    <th>Subject</th>
                                    <th>Body</th>
                                    <th>Details</th>
                                    <th>Copy</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <!-- Example Row (Repeat for each link in your database) -->
                        
                                    <tr>
                                    <td class="text-center"><?php echo $link_name?></td>
                                    <td class="text-center"><a href="<?php echo htmlspecialchars($baseUrl . 'links/shortcode_handler.php?shortcode=' . $mailto_short_code); ?>" target="_blank"><?php echo $_SERVER['HTTP_HOST']?>/<?php echo $link_data['short_code'] ?></a></td>
                                    <td class="text-center"><?php echo $reciever?></td>
                                    <td class="text-center"><?php echo $subject?></td>
                                    <td class="text-center"><?php echo $email_body?></td>
                                    <td class="text-center"> <a class="link_crud_icons" href="<?php echo htmlspecialchars($baseUrl . 'links/details.php?shortcode=' . $mailto_short_code); ?>"  title="View">
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
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?shortcode=" . $mailto_short_code; ?>" method="POST" class="mt-4">
                        <div class="form-group">
                            <label for="LinkName">Link Name</label>
                            <input type="text" id="LinkName" name="linkname" value="<?php echo $link_data['link_name']?>" class="form-control" placeholder="Enter Link Name" required>
                        </div>
                        <div class="form-group">
                            <label for="toEmail">To (Email Address)</label>
                            <input type="email" id="toEmail" name="toEmail" class="form-control" value="<?php echo $link_data['reciever']?>" placeholder="example@domain.com" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" value="<?php echo $link_data['subject']?>" placeholder="Enter email subject">
                        </div>
                        <div class="form-group">
                            <label for="body">Body Text</label>
                            <textarea id="body" name="body" class="form-control"  rows="4" maxlength="1000" placeholder="Enter email body text (max 1000 characters)"><?php echo $link_data['body']?></textarea>
                            <small class="form-text text-muted">Note: Different browsers may have limits on body length.</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Update Short Mailto Link</button>
                    </form>
                    <hr class="my-4">
                    <div id="result" class="mt-4">
                        <!-- Display shortened link here after form submission -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include($baseurl . '/includes/postjs.php') ?>

</body>
