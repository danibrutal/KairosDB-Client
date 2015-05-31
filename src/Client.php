<?php

namespace KairosDB;

use GuzzleHttp\Client as httpClient;

/**
 * Class Client
 * @package KairosDB
 */
class Client
{
    const API_VERSION = 'v1';

    private $base_uri;

    /** @var httpClient $httpClient */
    private $httpClient;

    /**
     * @param string $url
     */
    public function __construct($host = 'localhost', $port = 8080)
    {
        $this->base_uri = sprintf('http://%s:%s/api/%s/',
            $host, $port, self::API_VERSION
        );

        $this->httpClient = new httpClient(
            ['base_uri' => $this->base_uri]
        );
    }

    /**
     * For testing purposes
     * @param $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param $metricName
     * @param $value
     * @param array $tags
     * @param null $timestamp
     * @return string
     */
    public function addDataPoint($metricName, $value, array $tags, $timestamp = null)
    {
        return $this->post('datapoints', [
            'name' => $metricName,
            'tags' => $tags,
            'value'=> $value,
            'timestamp' => $timestamp ? $timestamp : round(microtime(true) * 1000)
        ]);
    }

    /**
     * @param DataPointCollection $dataPoints
     * @return string
     */
    public function addDataPoints(DataPointCollection $dataPoints)
    {
        return $this->post('datapoints', $dataPoints->toArray());
    }

    /**
     * @param array $query
     * @return string
     */
    public function query(array $query)
    {
        return $this->post('datapoints/query', $query);
    }

    /**
     * @return string
     */
    public function queryTags()
    {
        $data = [
            "start_absolute" => 1357023600000,
            "end_relative"=> [
                "value"=> "5",
                "unit"=> "days"
            ],
            "metrics" => [
                [
                    "tags" => [
                        "host" => "precise64"
                    ],
                    "name" => "kairosdb.protocol.http_request_count"
                ]
            ]
        ];

        return $this->post('datapoints/query/tags', $data);
    }

    public function deleteDataPoints($query = "")
    {
        $data = [
            "metrics" => [
                [
                    "tags" => [
                        "host" => "precise64"
                    ],
                    "name" => "kairosdb.protocol.http_request_count"
                ]
            ],
            "cache_time" => 0,
            "start_relative" => [
                "value" => "1",
                "unit" => "hours"
            ]
        ];
        return $this->post('datapoints/delete', $data);
    }

    public function deleteMetric($metricName)
    {
        return $this->delete(sprintf('metric/%s', $metricName));
    }

    /**
     * @return string
     */
    public function getMetricNames()
    {
        return $this->get('metricnames');
    }

    /**
     * @return string
     */
    public function getTagNames()
    {
        return $this->get('tagnames');
    }

    /**
     * @return string
     */
    public function getTagValues()
    {
        return $this->get('tagvalues');
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->get('version');
    }

    /**
     * @param $uri
     * @return string
     */
    private function get($uri)
    {
        $response = $this->httpClient->get($uri);

        return $response->getBody()->getContents();
    }

    /**
     * @param $uri
     * @return string
     */
    private function post($uri, array $data = [])
    {
        $response = $this->httpClient->post($uri, [
            'body' => json_encode($data)
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @param $uri
     * @return bool
     */
    private function delete($uri)
    {
        try {

            $this->httpClient->delete($uri);

        } catch (\Exception $e) {

            echo $e->getMessage();
            return false;
        }

        return true;
    }
}