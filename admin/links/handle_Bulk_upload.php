<?php
// Include necessary files
$baseurl = dirname(__DIR__, 1);
$vendorpath = dirname(__DIR__, 2);

include($baseurl . '/includes/head.php');
include($baseurl . '/db_connection.php');
require_once($vendorpath . '/vendor/phpqrcode/phpqrcode.php');
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    // Get file information
    $file = $_FILES['csvFile'];

    // Check for file upload errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $file['tmp_name'];
        $fileName = $file['name'];
        $fileType = $file['type'];
        $fileSize = $file['size'];

        // Check if the file is a CSV
        if ($fileType !== 'text/csv') {
            echo "Error: Please upload a valid CSV file.";
            exit();
        }

        // Open the file for reading
        if (($handle = fopen($fileTmpName, 'r')) !== false) {
            // Skip the header row if there is one
            fgetcsv($handle);

            // Begin transaction to ensure data consistency
            $conn->begin_transaction();

            try {
                // Read each row from the CSV
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    // Assign variables from CSV
                    $destination_url = $data[0];
                    $link_name = $data[1];
                    $status = $data[2] === 'active' ? 1 : 0;
                    $is_cloaking = $data[3] === 'yes' ? 1 : 0;
                    $cloaking_alias = $data[4] ?? null;
                    $expiry_date = !empty($data[5]) ? $data[5] : null;
                    $destination_after_expiry = $data[6] ?? null;
                    $link_password = $data[7] ?? null;
                    $one_time_view = $data[8] === 'yes' ? 1 : 0;
                    $social_title = $data[9] ?? null;
                    $social_description = $data[10] ?? null;
                   // Ensure country_target is a valid JSON format
                    $country_target = !empty($data[11]) ? $data[11] : '{}';
                    // If it's a plain string, you may want to encode it as JSON
                    $country_target = json_encode([$country_target]); // If you expect an array of countries

                    $countryDestination = $data[12] ?? null;
                    $device_target = $data[13] ?? null;
                    $deviceDestination = $data[14] ?? null;

                    // Generate short code
                    $short_code = substr(md5(uniqid(mt_rand(), true)), 0, 6);

                    // Prepare the SQL statement for inserting into the database
                    $stmt = $conn->prepare(
                        "INSERT INTO urls (
                            user_id,
                            destination_url, short_code, link_name, status, is_cloaking, cloaking_alias, 
                            social_title, social_description, expiry_date, 
                            destination_after_expiry, link_password, one_time_view, 
                            country_target, country_based_destination_link, device_target, device_based_destination_link
                        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
                    );

                    // Bind parameters
                    $stmt->bind_param(
                        'issssssssssssssss', 
                        $user_id, 
                        $destination_url, 
                        $short_code, 
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
                        $countryDestination, 
                        $device_target, 
                        $deviceDestination
                    );
                    

                    // Execute the query to insert the data into the database
                    if (!$stmt->execute()) {
                        throw new Exception("Error inserting data for $link_name: " . $stmt->error);
                    }

                    // Generate the QR code
                    $url = $short_code; // Replace with your domain
                    $qrcode_path = $baseurl . '/assets/images/qrcodes/' . $short_code . ".png";
                    QRcode::png($url, $qrcode_path, QR_ECLEVEL_L, 10);

                    // Optional: Display QR code for each inserted URL
                    // echo "QR Code generated for $link_name: <img src='$qrcode_path' /> <br>";
                }

                // Commit the transaction
                $conn->commit();
                $_SESSION['bulk_upload_message'] = "Bulk upload successful!";
                header("Location: " . $baseUrl . "links/view_all_links.php");
                exit();
            } catch (Exception $e) {
                // Rollback the transaction in case of error
                $conn->rollback();
                echo "Error: " . $e->getMessage();
            } finally {
                fclose($handle);
            }
        } else {
            echo "Error: Unable to open the file.";
        }
    } else {
        echo "Error: " . $file['error'];
    }
}
?>

