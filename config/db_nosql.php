<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

class DB_NoSQL {
    private static ?Client $client = null;

    public static function get(): Client {
        if (self::$client === null) {
            self::$client = new Client("mongodb://localhost:27017");;
        }

        return self::$client;
    }
}