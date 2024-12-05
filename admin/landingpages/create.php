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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $user_id =$user_id;
    $page_name = $_POST['landing_page_name'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $button_text = $_POST['button'];
    $destination_link = $_POST['landing_page_destinationLink'];
    $expiryDate = $_POST['expiryDate'];
    $destination_after_expiry = $_POST['destination_after_expiry'];
    $background_color = $_POST['background_color'];
    $text_color = $_POST['text_color'];
    $button_color = $_POST['button_color'];
    $font = $_POST['font'];
    $short_code = substr(md5(uniqid(mt_rand(), true)), 0, 6);

    // Prepare the SQL query
    $query = "INSERT INTO landingpages 
        (user_id, name, title, description, button_text, destination_link, short_link, expiryDate, destination_after_expiry, background_color, text_color, button_color, font) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "issssssssssss", 
        $user_id,
        $page_name,
        $title, 
        $description, 
        $button_text, 
        $destination_link, 
        $short_code, 
        $expiryDate,
        $destination_after_expiry,
        $background_color, 
        $text_color, 
        $button_color, 
        $font 
    );
    
    if ($stmt->execute()) {
        echo "Landing page saved successfully!";
        // Redirect to another page if needed
        // header("Location: landingpages_list.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


<body class="with-welcome-text">
  <!-- Navbar here -->
  <?php include($baseurl . '/includes/header.php') ?>

  <div class="container-fluid page-body-wrapper">
    <?php include($baseurl . '/includes/sidebar.php') ?>

    <style>
        /* Styling for the entire page background and container */
        body {
            background-color: #f8f9fa; /* Light grey background for the whole page */
            font-family: Arial, sans-serif;
        }
        .template-card {
            background-color: #fff; /* White card background */
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease;
            max-width: 400px;
            margin: auto;
            text-align: center;
            padding: 16px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .template-card.selected {
            border: 2px solid #1F3BB3; /* Blue border when template is selected */
            transform: scale(1.1); /* Increase size when selected */
        }
        .template-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        /* Image styling */
        .template-card img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        /* Card title and description styling */
        .template-card h5 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1F3BB3; /* Primary color for title */
            margin-bottom: 8px;
        }
        .template-card p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
        }
        /* Button styling inside template */
        .template-card .cta-button {
            background-color: #1F3BB3; /* Primary color for button */
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .template-card .cta-button:hover {
            background-color: #1b3298; /* Slightly darker primary color for hover effect */
        }
        /* Button styling for the form */
        .cta-button-form {
            background-color: #1F3BB3; /* Primary color for button */
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            display: block;
            margin: 20px auto;
        }
        .cta-button-form:hover {
            background-color: #1b3298; /* Slightly darker primary color for hover effect */
        }
    </style>

    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="card_title bg-primary text-white mt-3">
            <h3>Generate No Code Landing pages</h3>
          </div>
          <div class="container dashboard_card">
            <div class="container my-5">
              <!-- Templates Section -->
              <section id="templates-section" class="mb-5">
                <h2 class="text-left">Select a Template</h2>
                <div class="row g-3 mt-3">
                  <!-- Loop through your templates here -->
                  <div class="col-md-3">
                    <div class="card template-card" data-template-id="1" onclick="selectTemplate(1, 'Bakery', 'Freshly baked goods, cakes, and pastries made daily.', '<?php echo $baseUrl ?>assets/images/bakery_image.jpg')">
                      <img src="<?php echo $baseUrl ?>assets/images/bakery_image.jpg" alt="Bakery">
                      <h5>Bakery</h5>
                      <p>Freshly baked goods, cakes, and pastries made daily.</p>
                      <button class="cta-button">Explore Now</button>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card template-card" data-template-id="2" onclick="selectTemplate(2, 'Fashion', 'Freshly baked goods, cakes, and pastries made daily.', '<?php echo $baseUrl ?>assets/images/fashion.jpg')">
                      <img src="<?php echo $baseUrl ?>assets/images/fashion.jpg" alt="Bakery">
                      <h5>Fashion</h5>
                      <p>Freshly baked goods, cakes, and pastries made daily.</p>
                      <button class="cta-button">Explore Now</button>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card template-card" data-template-id="3" onclick="selectTemplate(3, 'Tech', 'Freshly baked goods, cakes, and pastries made daily.', '<?php echo $baseUrl ?>assets/images/tech.jpg')">
                      <img src="<?php echo $baseUrl ?>assets/images/tech.jpg" alt="Bakery">
                      <h5>Tech</h5>
                      <p>Freshly baked goods, cakes, and pastries made daily.</p>
                      <button class="cta-button">Explore Now</button>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="card template-card" data-template-id="4" onclick="selectTemplate(4, 'News', 'Freshly baked goods, cakes, and pastries made daily.', '<?php echo $baseUrl ?>assets/images/news_image.avif')">
                      <img src="<?php echo $baseUrl ?>assets/images/news_image.avif" alt="Bakery">
                      <h5>News</h5>
                      <p>Freshly baked goods, cakes, and pastries made daily.</p>
                      <button class="cta-button">Explore Now</button>
                    </div>
                  </div>
                  <!-- Repeat for other templates... -->
                </div>
              </section>
            
              <!-- Customization Section -->
              <section id="customization-section mt-4">
                <h2 class="text-left mb-4">Customize Your Landing Page</h2>
                <div class="row">
                  <!-- Editor Section -->
                  <div class="col-md-8">
                    <div class="card p-3 customize_form_container">
                      <form id="customizationForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
                      <div class="mb-3">
                          <label for="landing_page_name" class="form-label">Landing Page Name</label>
                          <input type="text" class="form-control" name="landing_page_name" id="landing_page_name" placeholder="Enter title">
                        </div>  
                        <div class="mb-3">
                          <label for="templateTitle" class="form-label">Title</label>
                          <input type="text" class="form-control" id="templateTitle" name="title" placeholder="Enter title">
                        </div>
                        <div class="mb-3">
                          <label for="templateDescription" class="form-label">Description</label>
                          <textarea class="form-control" id="templateDescription" name="description" rows="3" placeholder="Enter description"></textarea>
                        </div>
                        <div class="mb-3">
                          <label for="templateButton" class="form-label">Button Text</label>
                          <input type="text" class="form-control" id="templateButton" name="button" placeholder="Enter button text">
                        </div>
                      
                        <div class="form-group">
                                <label for="expiryDate" class="text-dark">Expiry Date & Time</label>
                                <input type="datetime-local" class="form-control" name="expiryDate" id="expiryDate">
                            </div>
                            <div class="form-group">
                                <label for="destination_after_expiry" class="text-dark">Destination After Expiry</label>
                                <input type="type" class="form-control" name="destination_after_expiry" id="destination_after_expiry">
                            </div>
                        <!-- Color and Font Selection -->
                        <div class="mb-3">
                            <div class="row">
                               <label for="colorPicker" class="form-label">Select Color</label>
                                <div class="col-4">
                                    <label for="colorPicker" class="form-label" style="font-size: 10px;">Background</label>
                                    <input type="color" id="background_colorPicker" name="background_color" class="form-control">
                                </div>
                                <div class="col-4">
                                    <label for="colorPicker" class="form-label" style="font-size: 10px;">Text</label>
                                    <input type="color" id="text_colorPicker" name="text_color" class="form-control">
                                </div>
                                <div class="col-4">
                                    <label for="colorPicker" class="form-label" style="font-size: 10px;">Button</label>
                                    <input type="color" id="button_colorPicker" name="button_color" class="form-control">
                                </div>
                            </div>
                        
                        </div>
                        <div class="mb-3" >
                          <label for="fontSelector" class="form-label">Select Font</label>
                          <select id="fontSelector" name="font" class="form-select">
                            <option value="Arial">Arial</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Verdana">Verdana</option>
                          </select>
                        </div>
                        <button type="button" class="btn btn-secondary" id="previewButton">Preview</button>
                        <button type="submit" class="btn btn-primary">Save Template</button>
                      </form>
                    </div>
                  </div>

                  <!-- Template Preview Section -->
                  <div class="col-md-4" style="height: 550px;">
                    <div id="templatePreview" style="height:100%" class="template-card">
                      <img src="<?php echo $baseUrl?>assets/images/place_holder_preview_image.jpg" id="previewImage" alt="Template Image">
                      <h5 id="previewTitle">Sample Title</h5>
                      <p id="previewDescription">Sample Description</p>
                      <button id="previewButtonText" class="cta-button">Sample Button</button>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
          <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2023. All rights reserved.</span>
        </div>
      </footer>
    </div>
  </div>

  <?php include($baseurl . '/includes/postjs.php') ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      // Handle template selection
      window.selectTemplate = function(id, title, description, image) {
        // Deselect all templates
        $('.template-card').removeClass('selected');
        // Select the clicked template
        $('div[data-template-id="' + id + '"]').addClass('selected');
        
        // Populate the customization form with the selected template's data
        $('#templateTitle').val(title);
        $('#templateDescription').val(description);
        $('#templateButton').val('Explore Now');
        $('#previewTitle').text(title);
        $('#previewDescription').text(description);
        $('#previewButtonText').text('Explore Now');
        $('#previewImage').attr('src', image);
      };

      // Handle preview generation
      $('#previewButton').click(function() {
        var background_color = $('#background_colorPicker').val();
        var text_color = $('#text_colorPicker').val();
        var button_color = $('#button_colorPicker').val();

        var font = $('#fontSelector').val();
        $('#templatePreview').css({
          'background-color': background_color,
          'font-family': font
        });
      });
    });

    const fontSelector = document.getElementById('fontSelector');

fontSelector.addEventListener('change', function () {
    console.log('Selected font:', this.value);
    // You can also use the selected text:
    console.log('Selected text:', this.options[this.selectedIndex].text);
});

  </script>
</body>
