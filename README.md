# KairosDB Client

## Description

A client library for KairosDB written in PHP.
This package provides convenience functions to read and write time series data.
It uses the HTTP protocol to communicate with your **KairosDB** cluster.

## Getting Started

### Install (Composer)

```bash
$ composer require 'danibrutal/kairosdb-client:dev-master'
```

### Connecting To Your Database

Connecting to an **KairosDB** database is straightforward. You will need a host
name a port. The default port is 8080.

For more information please check out the
[KairosDB Docs](http://kairosdb.github.io/kairosdocs/index.html).

### Inserting Data

We can add a single data point:

```php
require 'vendor/autoload.php';

$client = new KairosDB\Client('localhost', 8090);
$tags = ['host'=> 'precise64'];
$metricName = 'network_out';

for($i=2; $i<100; $i++) {
    $dataPointValue = $i *2;
    $client->addDataPoint($metricName, $dataPointValue, $tags);
    usleep(100);
}

```

Or using batch inserts:

```php
require 'vendor/autoload.php';

$client = new \KairosDB\Client('localhost', 8090);
$tags = ['host'=> 'precise64'];
$metricName = 'network_out';

$dataPointCollection = new \KairosDB\DataPointCollection($metricName, $tags);

for($i=2; $i<100; $i++) {
    $dataPointValue = $i *2;    
    $dataPointCollection->addPoint($dataPointValue);
    usleep(100);
}

$client->addDataPoints($dataPointCollection);
```


## Querying Data Points

The start date is required, but the end date defaults to NOW if not specified. The metric(s) that you are querying for is also required.
Optionally, tags may be added to narrow down the search.

```php
require 'vendor/autoload.php';

$client = new KairosDB\Client('localhost', 8090);

$queryBuilder = new \KairosDB\QueryBuilder();
$tags = ['host'=> 'precise64'];

$query = $queryBuilder
    ->start(['value'=> '1', 'unit' => 'days'])
    ->cache(10)
    ->addMetric('network_in')
    ->tags($tags)
    ->build();

$results = $client->query($query);
```


## Querying Metric Names

You can get a list of all metric names in KairosDB.

```php
require 'vendor/autoload.php';

$client = new KairosDB\Client('localhost', 8090);

$results = $client->getMetricNames($query);
```


## Querying Tag Names
Similarly you can get a list of all tag names in KairosDB.

```php
require 'vendor/autoload.php';

$client = new KairosDB\Client('localhost', 8090);

$results = $client->getTagNames($query);
```
	
## Querying Tag Values
And a list of all tag values.

```php
require 'vendor/autoload.php';

$client = new KairosDB\Client('localhost', 8090);

$results = $client->getTagValues($query);
```


## Custom Data Types
TODO: implement

## KairosDB Docs

Please refer to
[http://kairosdb.github.io/kairosdocs/](http://kairosdb.github.io/kairosdocs/)
for documentation.