<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, ORMSetup};

require_once __DIR__ . '/../vendor/autoload.php';

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../app/'],
    isDevMode: true,
);
try {
    $connection = DriverManager::getConnection([
        'driver' => 'pdo_mysql',
        'port' => 3306,
        'host' => 'mysql',
        'dbname' => 'framework_database',
        'user' => 'root',
        'password' => 'password',
    ], $config);

    return new EntityManager($connection, $config);
} catch (\Throwable $exception) {
    dd($exception->getMessage() . ' ' . $exception->getLine() . ' ' . $exception->getFile());
}