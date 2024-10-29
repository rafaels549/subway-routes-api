<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rafael\SubwayRoutesApi\Database\Entity\Manufacturer;
use Rafael\SubwayRoutesApi\Database\Database;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Rafael\SubwayRoutesApi\Middleware\ManufacturerValidationMiddleware;

return function (App $app) {
    $database = new Database();
    $entityManager = $database->getEntityManager();
    $app->group('/manufacturer', function (RouteCollectorProxy $group) use ($entityManager) {
        $group->post('/create', function (Request $request, Response $response) use ($entityManager) {
            $data = json_decode($request->getBody()->getContents(), true);
            $manufacturer = new Manufacturer(
                $data['name'],
                $data['contact_info']['phone'] ?? null,
                $data['contact_info']['email'] ?? null,
                $data['address']['street'] ?? null,
                $data['address']['city'] ?? null,
                $data['address']['state'] ?? null,
                $data['address']['zip_code'] ?? null,
                $data['website'] ?? null
            );
            $entityManager->persist($manufacturer);
            $entityManager->flush();
            $response->getBody()->write(json_encode([
                'message' => 'Manufacturer created successfully',
                'id' => $manufacturer->getId()
            ]));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        })->add(new ManufacturerValidationMiddleware());
    });
};
