<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Api\SubwayRoutes\Database\Entity\User;
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

$roles = $entityManager->getRepository(Role::class)->findAll();
$roleMapping = [];
foreach ($roles as $role) {
    $roleMapping[$role->getName()] = $role->getId();
}

$roleNames = ['Admin', 'Passenger', 'Train Operator', 'Ticket Booth Attendant', 'Maintenance Technician'];
$roleCount = count($roleNames);

for ($i = 1; $i <= 47; $i++) {
    $username = "user$i";
    $password = "password$i";
    $email = "user$i@example.com";
    $roleName = $roleNames[$i % $roleCount];
    $roleId = $roleMapping[$roleName];
    $phone = "12345678" . str_pad($i, 2, "0", STR_PAD_LEFT);
    $street = "Street $i";
    $city = "City $i";
    $country = "Country $i";
    $postal_code = "000$i";
    $state = "State $i";
    $date_of_birth = new \DateTime("199$i-01-01");
    $gender = $i % 2 === 0 ? 'Male' : 'Female';
    $nationality = "Nationality $i";
    $languages = "Language$i";

    $user = new User(
        $username,
        $password,
        $email,
        $roleId,
        $phone,
        $street,
        $city,
        $country,
        $postal_code,
        $state,
        $date_of_birth,
        $gender,
        $nationality,
        $languages
    );

    $entityManager->persist($user);
}

$entityManager->flush();

echo "Seed executed successfully.\n";
