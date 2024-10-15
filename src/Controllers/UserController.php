<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rafael\SubwayRoutesApi\Database\Entity\User;
use Rafael\SubwayRoutesApi\Database\Database;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Ramsey\Uuid\Uuid;

return function (App $app) {
    $database = new Database();
    $entityManager = $database->getEntityManager();

    $app->group('/user', function (RouteCollectorProxy $group) use ($entityManager) {

        $group->post('/create', function (Request $request, Response $response) use ($entityManager) {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $response->getBody()->write(json_encode(['error' => 'Invalid JSON format']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $requiredFields = ['username', 'password', 'role_id', 'contact', 'address', 'date_of_birth', 'gender', 'nationality', 'languages'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $response->getBody()->write(json_encode(['error' => "Missing required field: $field"]));
                    return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
                }
            }

            $roleId = Uuid::fromString($data['role_id']);
            $user = new User(
                $data['username'],
                $data['password'],
                $data['contact']['email'],
                $roleId,
                $data['contact']['phone'],
                $data['address']['street'],
                $data['address']['city'],
                $data['address']['country'],
                $data['address']['postal_code'],
                $data['address']['state'],
                new \DateTime($data['date_of_birth']),
                $data['gender'],
                $data['nationality'],
                $data['languages']
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $response->getBody()->write(json_encode(['message' => 'User created successfully']));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        });

    });
};
