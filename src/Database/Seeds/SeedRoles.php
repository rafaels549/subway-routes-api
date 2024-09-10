<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Rafael\SubwayRoutesApi\Entity\Role;
use Dotenv\Dotenv;
use Ramsey\Uuid\Doctrine\UuidType;
use Doctrine\DBAL\Types\Type;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

if (!Type::hasType('uuid')) {
    Type::addType('uuid', UuidType::class);
}


$paths = [__DIR__ . '/../Entity'];
$isDevMode = true;

$dbParams = [
    'driver'   => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname'   => $_ENV['DB_DATABASE'],
    'host'     => $_ENV['DB_HOST'],
];

$config = Setup::createAttributeMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);


$roles = [
    'Admin' => 'Role with all permissions',
    'Passenger' => 'Standard user role with access to ticketing and schedules',
    'Train Operator' => 'Responsible for operating the train',
    'Ticket Booth Attendant' => 'Manages ticket sales and customer service at the booth',
    'Maintenance Technician' => 'Handles the maintenance and repair of trains and facilities'
];

foreach ($roles as $roleName => $roleDescription) {
    $role = new Role($roleName, $roleDescription);
    $entityManager->persist($role);
}

$entityManager->flush();

echo "Seed executed successfully.\n";
