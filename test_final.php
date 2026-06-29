<?php

require __DIR__ . '/vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb://localhost:27017");

echo "Mongo OK";