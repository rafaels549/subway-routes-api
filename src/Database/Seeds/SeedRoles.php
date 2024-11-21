<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Api\SubwayRoutes\Database\Entity\Role;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;
use Ramsey\Uuid\Doctrine\UuidType;
use Doctrine\DBAL\Types\Type;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

if (!Type::hasType('uuid')) {
    Type::addType('uuid', UuidType::class);
}

$config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../Entity'], true);

$connection = DriverManager::getConnection([
    'driver'   => $_ENV['DB_DRIVER'],
    'host'     => $_ENV['DB_HOST'],
    'port'     => $_ENV['DB_PORT'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname'   => $_ENV['DB_NAME'],
    'charset'  => $_ENV['DB_CHARSET'],
    'sslmode'  => 'disable',
], $config);

$entityManager = new EntityManager($connection, $config);

$roles = [
    'Admin' => 'Role with all permissions',
    'Passenger' => 'Standard user role with access to ticketing and schedules',
    'Train Operator' => 'Responsible for operating the train',
    'Ticket Booth Attendant' => 'Manages ticket sales and customer service at the booth',
    'Maintenance Technician' => 'Handles the maintenance and repair of trains and facilities',
];

foreach ($roles as $roleName => $roleDescription) {
    $entityManager->persist(new Role($roleName, $roleDescription));
}

$entityManager->flush();

echo "Seed executed successfully.\n";
