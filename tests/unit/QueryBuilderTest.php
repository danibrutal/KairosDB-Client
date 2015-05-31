<?php

namespace KairosDB\Test;

use KairosDB\QueryBuilder;

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  QueryBuilder $queryBuilder */
    private $queryBuilder;

    public function setUp()
    {
        $this->queryBuilder = new QueryBuilder();
    }

    public function testAddSeveralMetric()
    {
        $metricName = "network_in";
        $metricName2 = "network_out";
        $query = $this->queryBuilder->addMetric($metricName)->addMetric($metricName2)->build();
        $this->assertArrayHasKey('metrics', $query);

        $this->assertCount(2, $query['metrics']);
    }

    public function testAddTagsToMetric()
    {
        $metricName = "network_in";
        $tags = ['host' => 'precise64'];
        $query = $this->queryBuilder
            ->addMetric($metricName)
            ->tags($tags)
            ->build();

        $metric = array_pop($query['metrics']);

        $this->assertArrayHasKey('tags', $metric);

    }

    public function testAddLimiToMetric()
    {
        $metricName = "network_in";
        $query = $this->queryBuilder
            ->addMetric($metricName)
            ->limit(700)
            ->build();

        $metric = array_pop($query['metrics']);

        $this->assertArrayHasKey('limit', $metric);

    }

    public function testAddMetric()
    {
        $metricName = "network_in";
        $query = $this->queryBuilder->addMetric($metricName)->build();
        $this->assertArrayHasKey('metrics', $query);
        $metric = array_pop($query['metrics']);

        $this->assertEquals($metricName, $metric['name']);
    }

    public function testStartFromRelative()
    {
        $query = $this->queryBuilder->start(['value' => 1, 'unit' => 'days'])->build();
        $this->assertArrayHasKey('start_relative', $query);
    }

    public function testStartFromAbsolute()
    {
        $start = round(microtime(true) * 1000);
        $query = $this->queryBuilder->start($start)->build();
        $this->assertArrayHasKey('start_absolute', $query);
    }

    public function testEndRelative()
    {
        $query = $this->queryBuilder->end(['value' => 1, 'unit' => 'days'])->build();
        $this->assertArrayHasKey('end_relative', $query);
    }

    public function testEndAbsolute()
    {
        $start = round(microtime(true) * 1000);
        $query = $this->queryBuilder->end($start)->build();
        $this->assertArrayHasKey('end_absolute', $query);
    }

    public function testQueryCacheTime()
    {
        $seconds = 60*60;
        $query = $this->queryBuilder->cache($seconds)->build();
        $this->assertArrayHasKey('cache_time', $query);
    }

} 