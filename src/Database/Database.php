<?php
namespace Rafael\SubWayRoutesApi\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

class Database
{
    private EntityManager $entityManager;

    public function __construct()
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../Entity'], 
            isDevMode: true
        );

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
        ];

        $connection = DriverManager::getConnection($connectionParams, $config);
        $this->entityManager = new EntityManager($connection, $config);
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}
