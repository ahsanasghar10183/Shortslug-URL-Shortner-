<?php
// Include the GoogleAnalyticsService file
require_once 'analytics.php';

// Usage
$credentialsPath = 'C:/laragon/www/url_shortner_app/admin/includes/analytics-key.json';
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container my-4">
    <!-- Chart for Reach by Country -->
    <div class="row mb-4">
        <div class="col-12">
            <h4>Reach By Country</h4>
            <canvas id="countryChart"></canvas>
        </div>
    </div>

    <!-- Chart for Reach by Device -->
    <div class="row">
        <div class="col-12">
            <h4>Reach By Device Category</h4>
            <canvas id="deviceChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'New Users',
                    data: countryNewUsers,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Active Users',
                    data: countryActiveUsers,
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
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
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'New Users',
                    data: deviceNewUsers,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Active Users',
                    data: deviceActiveUsers,
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
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
</script>
</body>
</html>
