<?php

require 'vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Ramsey\Uuid\Doctrine\UuidType;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Register UUID type if not already registered
if (!Type::hasType('uuid')) {
    Type::addType('uuid', UuidType::class);
}

// ORM and DBAL configuration
$paths = [__DIR__ . '/src/Database/Entity'];
$isDevMode = true;

$ORMConfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$connectionParams = [
    'dbname'   => $_ENV['DB_DATABASE'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'host'     => $_ENV['DB_HOST'],
    'driver'   => 'pdo_' . $_ENV['DB_DRIVER'],
];

$connection = DriverManager::getConnection($connectionParams, $ORMConfig);

// Create the EntityManager
$entityManager = EntityManager::create($connection, $ORMConfig);

// Load migration configuration from migrations.php
$config = new PhpFile('migrations.php'); 

// Return the DependencyFactory for migrations
return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
