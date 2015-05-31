<?php

namespace KairosDB;

/**
 * Class DataPointCollection
 * @package KairosDB
 */
class DataPointCollection
{
    /** @var array $points */
    private $points = [];

    /** @var array $tags */
    private $tags = [];

    /** @var  string $metricName */
    private $metricName;

    /**
     * @param $metricName
     * @param array $tags
     */
    public function __construct($metricName, array $tags)
    {
        $this->metricName = $metricName;
        $this->tags = $tags;
    }

    public function toArray()
    {
        return [
            'name'=> $this->metricName,
            'tags'=> $this->tags,
            'datapoints' => $this->points
        ];
    }

    /**
     * @param $value
     * @param null $timestamp
     */
    public function addPoint($value, $timestamp = null)
    {
        $timestamp = is_null($timestamp) ? round(microtime(true) * 1000) : $timestamp;
        $this->points[] = [$timestamp, $value];
    }

    /**
     * @return string
     */
    public function getMetricName()
    {
        return $this->metricName;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

} 