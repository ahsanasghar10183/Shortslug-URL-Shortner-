<?php
require_once 'C:\laragon\www\url_shortner_app\vendor\autoload.php';

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\Filter\StringFilter;

function initializeAnalytics($credentialsPath)
{
    // Initialize the client using the Google Analytics Data API
    $client = new BetaAnalyticsDataClient(['credentials' => $credentialsPath]);
    return $client;
}



function getReport($client, $propertyId, $url)
{
    // Define the date range for the report.
    $dateRange = new DateRange();
    $dateRange->setStartDate('7daysAgo');
    $dateRange->setEndDate('today');

    // Define the dimension for the page path (URL).
    $dimension = new Dimension();
    $dimension->setName('pagePath');

    // Define the metric for page views.
    $metric = new Metric();
    $metric->setName('screenPageViews');

    // Create a StringFilter for the specific page path (URL).
    $stringFilter = new StringFilter();
    $stringFilter->setMatchType(StringFilter\MatchType::EXACT);
    $stringFilter->setValue($url);

    // Create a Filter using the StringFilter.
    $filter = new Filter();
    $filter->setFieldName('pagePath');
    $filter->setStringFilter($stringFilter);

    $filterExpression = new FilterExpression();
    $filterExpression->setFilter($filter);

    // Run the report query.
    $response = $client->runReport([
        'property' => 'properties/' . $propertyId,
        'dateRanges' => [$dateRange], // Ensure dateRanges key is camel case here
        'dimensions' => [$dimension],
        'metrics' => [$metric],
        'dimensionFilter' => $filterExpression
    ]);

    return $response;
}

function printResults($response)
{
    foreach ($response->getRows() as $row) {
        $dimensions = $row->getDimensionValues();
        $metrics = $row->getMetricValues();
        echo "URL: " . $dimensions[0]->getValue() . "<br>";
        echo "Pageviews: " . $metrics[0]->getValue() . "<br><br>";
    }
}

// Path to your JSON credentials file from Google Cloud Console
$credentialsPath = 'C:/laragon/www/url_shortner_app/admin/includes/analytics-key.json';

// GA4 Property ID
$propertyId = '449078644';

// URL path you want to retrieve data for
$url = '/taylor-swift-2048'; // Update this to the page path you want

// Initialize the Analytics Data API client
$client = initializeAnalytics($credentialsPath);

// Fetch and display the report data
$response = getReport($client, $propertyId, $url);
printResults($response);
