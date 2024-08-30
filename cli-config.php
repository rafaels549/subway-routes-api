<?php

declare(strict_types = 1);

require 'vendor/autoload.php';

use Doctrine\DBAL\Types\Type;
use Ramsey\Uuid\Doctrine\UuidType;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Dotenv\Dotenv;

// Carrega as variáveis de ambiente
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Registrar o tipo UUID no DBAL
if (!Type::hasType('uuid')) {
    Type::addType('uuid', UuidType::class);
}

$config = new PhpFile('migrations.php'); // Ou use um dos carregadores de Configuration\*
$params = [
    'host'     => $_ENV['DB_HOST'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname'   => $_ENV['DB_DATABASE'],
    'driver'   => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
];

$entityManager = EntityManager::create(
    $params,
    Setup::createAttributeMetadataConfiguration([__DIR__ . '/src/Database/Entity'])
);

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
