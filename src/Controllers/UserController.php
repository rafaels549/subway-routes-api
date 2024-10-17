<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rafael\SubwayRoutesApi\Database\Entity\User;
use Rafael\SubwayRoutesApi\Database\Database;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Validator as v;
use Rafael\SubwayRoutesApi\Middleware\UserValidationMiddleware;

return function (App $app) {
    $database = new Database();
    $entityManager = $database->getEntityManager();

    $app->group('/user', function (RouteCollectorProxy $group) use ($entityManager) {
        $group->post('/create', function (Request $request, Response $response) use ($entityManager) {
            $data = json_decode($request->getBody()->getContents(), true);

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

        try{
                $entityManager->persist($user);
                $entityManager->flush();
                    
                $response->getBody()->write(json_encode(['message' => 'User created successfully']));
                return $response->withStatus(201)->withHeader('Content-Type', 'application/json');

        }catch(\Exception $e){

                $response->getBody()->write(json_encode(['message' => 'Failed to create user', 'details' => $e->getMessage()]));
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
                
        }

        })->add(new UserValidationMiddleware());
    });
};
