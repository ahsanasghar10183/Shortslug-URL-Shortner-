<?php
require_once 'C:\laragon\www\shortslug\vendor\autoload.php';

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\Filter\StringFilter;

class GoogleAnalyticsService
{
    private $client;
    private $propertyId;

    public function __construct($credentialsPath, $propertyId)
    {
        $this->client = new BetaAnalyticsDataClient(['credentials' => $credentialsPath]);
        $this->propertyId = $propertyId;
    }

    public function getAnalyticsDataForUrl($url, $startDate, $endDate)
    {
        // Fetch data grouped by country
        $dataByCountry = $this->fetchReport($url, $startDate, $endDate, 'country');
        
        // Fetch data grouped by device category
        $dataByDevice = $this->fetchReport($url, $startDate, $endDate, 'deviceCategory');

        return compact('dataByCountry', 'dataByDevice');
    }

    private function fetchReport($url, $startDate, $endDate, $dimensionName)
    {
        $dateRange = new DateRange();
        $dateRange->setStartDate($startDate);
        $dateRange->setEndDate($endDate);

        $dimension = new Dimension();
        $dimension->setName($dimensionName);

        $metrics = [
            (new Metric())->setName('screenPageViews'),
            (new Metric())->setName('newUsers'),
            (new Metric())->setName('activeUsers'),
            (new Metric())->setName('bounceRate'),
            (new Metric())->setName('userEngagementDuration'),
        ];

        $stringFilter = new StringFilter();
        $stringFilter->setMatchType(StringFilter\MatchType::EXACT);
        $stringFilter->setValue($url);

        $filter = new Filter();
        $filter->setFieldName('pagePath');
        $filter->setStringFilter($stringFilter);

        $filterExpression = new FilterExpression();
        $filterExpression->setFilter($filter);

        try {
            $response = $this->client->runReport([
                'property' => 'properties/' . $this->propertyId,
                'dateRanges' => [$dateRange],
                'dimensions' => [$dimension],
                'metrics' => $metrics,
                'dimensionFilter' => $filterExpression,
            ]);

            return $this->formatResponse($response);
        } catch (Exception $e) {
            error_log('Google Analytics API Error: ' . $e->getMessage());
            return null;
        }
    }

    public function formatResponse($response)
    {
        $formattedData = [];
        foreach ($response->getRows() as $row) {
            $dimensions = array_map(fn($item) => $item->getValue(), iterator_to_array($row->getDimensionValues()));
            $metrics = array_map(fn($item) => $item->getValue(), iterator_to_array($row->getMetricValues()));
    
            $formattedData[] = [
                'dimensions' => $dimensions,
                'metrics' => $metrics,
            ];
        }
        return $formattedData;
    }
    
}

// Usage
$credentialsPath = 'C:/laragon/www/shortslug/admin/includes/analytics-key.json';
$propertyId = '449078644';

$service = new GoogleAnalyticsService($credentialsPath, $propertyId);

// Replace with your desired URL, start date, and end date
$url = '/taylor-swift-2048';
$startDate = '2024-11-01';
$endDate = '2024-11-07';

$data = $service->getAnalyticsDataForUrl($url, $startDate, $endDate);



?>
