<?php
namespace Rafael\SubWayRoutesApi\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Validator as v;

class UserValidationMiddleware {
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response {
        $data = json_decode($request->getBody()->getContents(), true);
        $response = new \Slim\Psr7\Response();

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->errorResponse($response, 'Invalid JSON', 400);
        }

        $validation = v::arrayType()
            ->key('username', v::stringType()->notEmpty())
            ->key('password', v::stringType()->length(8, null))
            ->key('role_id', v::uuid())
            ->key('contact', v::arrayType()->key('email', v::email())->key('phone', v::phone()))
            ->key('address', v::arrayType()->key('street', v::stringType())->key('city', v::stringType())->key('country', v::stringType())->key('postal_code', v::stringType())->key('state', v::stringType()))
            ->key('date_of_birth', v::date('Y-m-d'))
            ->key('gender', v::stringType())
            ->key('nationality', v::stringType())
            ->key('languages', v::stringType());

        try {
            $validation->assert($data);
            $this->validatePassword($data['password']);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            $errors = $this->formatErrors($e->getMessages());
            return $this->errorResponse($response, $errors, 400);
        } catch (\Exception $e) {
            return $this->errorResponse($response, $e->getMessage(), 400);
        }

        $request = $request->withParsedBody($data);
        return $handler->handle($request);
    }

    private function validatePassword($password) {
        $messages = [];
        if (!preg_match('/[A-Z]/', $password)) {
            $messages[] = 'A senha deve conter pelo menos uma letra maiúscula.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $messages[] = 'A senha deve conter pelo menos uma letra minúscula.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $messages[] = 'A senha deve conter pelo menos um número.';
        }
        if (!preg_match('/[\W]/', $password)) {
            $messages[] = 'A senha deve conter pelo menos um caractere especial.';
        }
        if (count($messages) > 0) {
            throw new \Exception(implode(', ', $messages));
        }
    }

    private function errorResponse(Response $response, $message, $status) {
        $response->getBody()->write(json_encode(['message' => $message]));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }

    private function formatErrors(array $errors): array {
        $formattedErrors = [];
        foreach ($errors as $key => $messages) {
            if (is_array($messages)) {
                $formattedErrors[$key] = implode(", ", $messages);
            } else {
                $formattedErrors[$key] = $messages;
            }
        }
        return $formattedErrors;
    }
}
