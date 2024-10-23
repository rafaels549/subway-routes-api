<?php
namespace Rafael\SubWayRoutesApi\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;
use Doctrine\DBAL\Types\Type;
use Ramsey\Uuid\Doctrine\UuidType;

class Database
{
    private EntityManager $entityManager;

    public function __construct()
    {
        if (!Type::hasType('uuid')) {
            Type::addType('uuid', UuidType::class);
        }
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../Entity'], 
            isDevMode: true,
            cache: null
        );
        $config->addCustomNumericFunction('UUID', 'Ramsey\Uuid\Doctrine\UuidGenerator');
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
       
        $connectionParams = [
            'driver' => $_ENV['DB_DRIVER'],
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'dbname' => $_ENV['DB_NAME'],
            'charset' => $_ENV['DB_CHARSET'],
            'sslmode' => 'disable'
        ];
        $connection = DriverManager::getConnection($connectionParams, $config);
        $this->entityManager = new EntityManager($connection, $config);
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}
