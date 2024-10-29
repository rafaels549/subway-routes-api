<?php

namespace Rafael\SubwayRoutesApi\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class ManufacturerValidationMiddleware
{
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $nameValidator = v::stringType()->notEmpty();
        $emailValidator = v::optional(v::email());
        $phoneValidator = v::optional(v::stringType());
        $websiteValidator = v::optional(v::url());

        try {
            $nameValidator->check($data['name']);
            if (isset($data['contact_info']['email'])) {
                $emailValidator->check($data['contact_info']['email']);
            }
            if (isset($data['contact_info']['phone'])) {
                $phoneValidator->check($data['contact_info']['phone']);
            }
            if (isset($data['website'])) {
                $websiteValidator->check($data['website']);
            }
            return $handler->handle($request);
        } catch (NestedValidationException $e) {
            $response = new Response();
            $response->getBody()->write(json_encode([
                'message' => 'Validation error',
                'errors' => $e->getMessages(),
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}
