<?php

require './vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Ramsey\Uuid\Doctrine\UuidType;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!Type::hasType('uuid')) {
    Type::addType('uuid', UuidType::class);
}

$paths = [__DIR__ . '/src/Database/Entity'];
$isDevMode = true;

$ORMConfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connectionParams = [
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'],
    'driver' => $_ENV['DB_DRIVER'],
];

$connection = DriverManager::getConnection($connectionParams);
$entityManager = new EntityManager($connection, $ORMConfig);
$config = new PhpFile('migrations.php');

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
