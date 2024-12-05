<?php
include (__DIR__ . '/includes/head.php');
include(__DIR__. '/db_connection.php');
if(!isset($_SESSION)){
  session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}
$user_id = $_SESSION['user_id'];

// Include the GoogleAnalyticsService file
require_once (__DIR__ . '/analytics/analytics.php');

// Usage
$credentialsPath = 'C:/laragon/www/shortslug/admin/includes/analytics-key.json';
$propertyId = '449078644';

// Initialize the Google Analytics Service
$service = new GoogleAnalyticsService($credentialsPath, $propertyId);

// Replace with your desired URL, start date, and end date
$url = '/taylor-swift-2048';
$startDate = '2023-11-01';
$endDate = '2024-11-07';

// Fetch analytics data
$data = $service->getAnalyticsDataForUrl($url, $startDate, $endDate);
$dataByCountry = $data['dataByCountry'];
$dataByDevice = $data['dataByDevice'];

// Convert PHP data to JSON for use in JavaScript
$countryDataJson = json_encode($dataByCountry);





if (isset($_GET['shortcode'])) {
  $shortcode = $_GET['shortcode'];
}
$hitsquery = "SELECT hits FROM urls WHERE short_code = ?";
$hitsstmt = $conn->prepare($hitsquery);
$hitsstmt->bind_param("s", $shortcode); // Use the actual short_code value
$hitsstmt->execute();
$hitsdata = $hitsstmt->get_result();
$row = $hitsdata->fetch_row();
$totalhits = $row[0] ?? 0;
 // The hits count is in the first column
// today hits fetching

// fetching total short links
$Total_linksquery = "SELECT count(short_code) FROM urls";
$Total_linksstms = $conn->prepare($Total_linksquery);
$Total_linksstms->execute();
$Total_linksdata = $Total_linksstms->get_result();
$row = $Total_linksdata->fetch_row();
$Total_LinksCount = $row[0]; 
// fetching the number of active Links
$linkstatus = 1;
$activelinksquery = "SELECT count(short_code) FROM urls WHERE status = ?";
$activelinksstms = $conn->prepare($activelinksquery);
$activelinksstms->bind_param("i", $linkstatus);
$activelinksstms->execute();
$activelinksdata = $activelinksstms->get_result();
$row = $activelinksdata->fetch_row();
$activeLinksCount = $row[0]; 
// fethcing Inactive Links
$inlinkstatus = 0;
$inactivelinksquery = "SELECT count(short_code) FROM urls WHERE status = ?";
$inactivelinksstms = $conn->prepare($inactivelinksquery);
$inactivelinksstms->bind_param("i", $inlinkstatus);
$inactivelinksstms->execute();
$inactivelinksdata = $inactivelinksstms->get_result();
$row = $inactivelinksdata->fetch_row();
$inactiveLinks = $row[0]; 

//  fetching nummber of expired links
$expired_link_status = 2;
$expiredlinksquery = "SELECT count(short_code) FROM urls WHERE status = ?";
$expiredlinksstms = $conn->prepare($activelinksquery);
$expiredlinksstms->bind_param("i", $expired_link_status);
$expiredlinksstms->execute();
$expiredlinksdata = $expiredlinksstms->get_result();
// Fetch the count value
$row = $expiredlinksdata->fetch_row();
$expiredLinksCount = $row[0]; 


$totallinks = $Total_LinksCount;

 ?>
  <body class="with-welcome-text">
   <!-- Navbar here -->
    <?php include('./includes/header.php')?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
       <!----Sidebar Injecting here--->
       <?php include('./includes/sidebar.php')?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-sm-12">
                <div class="home-tab">
                  <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                      </li>
                     
                    </ul>
                    <div>
                      <div class="btn-wrapper">
                        <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
                        <a href="#" class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                        <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
                      </div>
                    </div>
                  </div>
                  <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                        <div class="col-lg-8 d-flex flex-column">
                          <div class="row flex-grow">
                            <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                              <div class="card card-rounded">
                                <div class="card-body">
                                  <div class="d-sm-flex justify-content-between align-items-start">
                                    <div>
                                      <h4 class="card-title card-title-dash">Reach By Device</h4>
                                      <h5 class="card-subtitle card-subtitle-dash">Track Link Analytics By Device Catagory</h5>
                                    </div>
                                    <div id="performanceLine-legend"></div>
                                  </div>
                                  <div class="chartjs-wrapper mt-4">
                                    <canvas id="deviceChart" width=""></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 d-flex flex-column">
                          <div class="row flex-grow">
                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                              <div class="card bg-primary card-rounded">
                                <div class="card-body pb-0">
                                  <h4 class="card-title card-title-dash text-white mb-4">Status Summary</h4>
                                  <div class="row">
                                    <div class="col-sm-4">
                                      <p class="status-summary-ight-white mb-1">Active Links</p>
                                      <h2 class="text-info"><?php echo $Total_LinksCount?></h2>
                                    </div>
                                    <div class="col-sm-8">
                                      <div class="status-summary-chart-wrapper pb-4">
                                        <canvas id="status-summary"></canvas>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                              <div class="card card-rounded">
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <div class="d-flex justify-content-between align-items-center mb-2 mb-sm-0">
                                        <div class="circle-progress-width">
                                          <div id="totalVisitors" class="progressbar-js-circle pr-2"></div>
                                        </div>
                                        <div>
                                          <p class="text-small mb-2">Total Clicks</p>
                                          <h4 class="mb-0 fw-bold"><?php echo $totalhits?></h4>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-lg-6">
                                      <div class="d-flex justify-content-between align-items-center">
                                        <div class="circle-progress-width">
                                          <div id="visitperday" class="progressbar-js-circle pr-2"></div>
                                        </div>
                                        <div>
                                          <p class="text-small mb-2">Total Short links</p>
                                          <h4 class="mb-0 fw-bold"><?php echo $Total_LinksCount?></h4>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>   
                      <div class="col-lg-12 d-flex flex-column">
                          <div class="row flex-grow">
                            <!-- First Card: Links Overview -->
                            <div class="col-md-8 grid-margin stretch-card">
                              <div class="card card-rounded">
                                <div class="card-body">
                                  <div class="d-sm-flex justify-content-between align-items-start">
                                    <div>
                                      <h4 class="card-title card-title-dash">Reach By Country</h4>
                                      <p class="card-subtitle card-subtitle-dash">Track Link Analytics By Country</p>
                                    </div>
                                    <div>
                                     
                                    </div>
                                  </div>
                               
                                  <div class="chartjs-bar-wrapper mt-3">
                                    <canvas id="countryChart"></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Second Card: Type By Amount -->
                            <div class="col-md-4 grid-margin stretch-card">
                              <div class="card card-rounded">
                                <div class="card-body">
                                  <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title card-title-dash">Type By Amount</h4>
                                  </div>
                                  <div style="padding-top:20px; ">
                                    <canvas class="my-auto" id="linksDataChart"></canvas>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
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
   
  
    <?php include('./includes/postjs.php')?>

    <script>
    // Data for Country Chart
    var dataByCountry = <?php echo json_encode($dataByCountry); ?>;
    var countryLabels = dataByCountry.map(function(item) {
        return item.dimensions[0]; // Country names
    });

    // Extract the first three dimensions: Views, New Users, Active Users
    var countryViews = dataByCountry.map(function(item) {
        return item.metrics[0]; // Views
    });
    var countryNewUsers = dataByCountry.map(function(item) {
        return item.metrics[1]; // New Users
    });
    var countryActiveUsers = dataByCountry.map(function(item) {
        return item.metrics[2]; // Active Users
    });

    // Data for Device Chart
    var dataByDevice = <?php echo json_encode($dataByDevice); ?>;
    var deviceLabels = dataByDevice.map(function(item) {
        return item.dimensions[0]; // Device categories
    });

    // Extract the first three dimensions: Views, New Users, Active Users
    var deviceViews = dataByDevice.map(function(item) {
        return item.metrics[0]; // Views
    });
    var deviceNewUsers = dataByDevice.map(function(item) {
        return item.metrics[1]; // New Users
    });
    var deviceActiveUsers = dataByDevice.map(function(item) {
        return item.metrics[2]; // Active Users
    });

    // Render the Country Chart
    var ctx1 = document.getElementById('countryChart').getContext('2d');
    var countryChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: countryLabels,
            datasets: [
                {
                    label: 'Views',
                    data: countryViews,
                    backgroundColor: 'rgba(54, 162, 235)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'New Users',
                    data: countryNewUsers,
                    backgroundColor: 'rgba(75, 192, 192)',
                    borderColor: 'rgba(75, 192, 192)',
                    borderWidth: 1
                },
                {
                    label: 'Active Users',
                    data: countryActiveUsers,
                    backgroundColor: 'rgba(255, 159, 64)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,  // Ensure chart adapts to container height
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        tickColor: 'rgba(0, 0, 0, 0.1)',
                    },
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 12,
                            weight: 'normal',
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 12,
                            weight: 'normal',
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 10,
                            weight: 'normal',
                        },
                        usePointStyle: true,
                        pointStyle: 'circle',
                    },
                    align: 'end',
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        family: 'Arial, sans-serif',
                        size: 14,
                        weight: 'normal',
                    },
                    bodyFont: {
                        family: 'Arial, sans-serif',
                        size: 12,
                    },
                    borderWidth: 0,
                    cornerRadius: 5,
                    padding: 10,
                    displayColors: false,
                }
            },
        }
    });

    // Render the Device Chart
    var ctx2 = document.getElementById('deviceChart').getContext('2d');
    var deviceChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: deviceLabels,
            datasets: [
                {
                    label: 'Views',
                    data: deviceViews,
                    backgroundColor: 'rgba(54, 162, 235)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'New Users',
                    data: deviceNewUsers,
                    backgroundColor: 'rgba(75, 192, 192)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Active Users',
                    data: deviceActiveUsers,
                    backgroundColor: 'rgba(255, 159, 64)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,  // Ensure chart adapts to container height
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        tickColor: 'rgba(0, 0, 0, 0.1)',
                    },
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 12,
                            weight: 'normal',
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 12,
                            weight: 'normal',
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            family: 'Arial, sans-serif',
                            size: 10,
                            weight: 'normal',
                        },
                        usePointStyle: true,
                        pointStyle: 'circle',
                    },
                    align: 'end',
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        family: 'Arial, sans-serif',
                        size: 14,
                        weight: 'normal',
                    },
                    bodyFont: {
                        family: 'Arial, sans-serif',
                        size: 12,
                    },
                    borderWidth: 0,
                    cornerRadius: 5,
                    padding: 10,
                    displayColors: false,
                }
            },
        }
    });

    // Ensure these variables are correctly set from PHP
    const Total_LinksCount = <?php echo (int)$Total_LinksCount; ?>;
    const activeLinksCount = <?php echo (int)$activeLinksCount; ?>;
    const inactiveLinks = <?php echo (int)$inactiveLinks; ?>;
    const expiredLinksCount = <?php echo (int)$expiredLinksCount; ?>;
  
    // Get the canvas element
    const doughnutChartCanvas = document.getElementById('linksDataChart');

    // Create the doughnut chart
    new Chart(doughnutChartCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Total', 'Active', 'Inactive', 'Expired'],  // Labels
            datasets: [{
                data: [Total_LinksCount, activeLinksCount, inactiveLinks, expiredLinksCount],  // Data values
                backgroundColor: [
                    "#1F3BB3",  // Total color
                    "#FDD0C7",  // Active color
                    "#52CDFF",  // Inactive color
                    "#81DADA"   // Expired color
                ],
                borderColor: [
                    "#1F3BB3",  // Total border color
                    "#FDD0C7",  // Active border color
                    "#52CDFF",  // Inactive border color
                    "#81DADA"   // Expired border color
                ],
                borderWidth: 1,  // Border width for slices
            }]
        },
        options: {
            cutout: 70,  // Makes the center empty, creating the doughnut shape
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',  // Position of the legend
                    labels: {
                      font: {
                            family: 'Arial, sans-serif',
                            size: 10,
                            weight: 'normal',
                        },
                        boxWidth: 10,  // Size of the box next to each legend item
                        padding: 10,   // Padding between legend items
                        usePointStyle: true,
                    }
                    
                }
            }
        }
    });


</script>

  </body>



