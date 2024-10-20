<?php
namespace Rafael\SubWayRoutesApi\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class UserValidationMiddleware {
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response {
        $data = json_decode($request->getBody()->getContents(), true);
        $response = new \Slim\Psr7\Response();

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->errorResponse($response, ['Invalid JSON'], 400);
        }

        $validation = v::arrayType()
            ->key('username', v::stringType()->notEmpty()->setName('username'))
            ->key('password', v::stringType()->setName('password')) 
            ->key('role_id', v::uuid())
            ->key('contact', v::arrayType()
                ->key('email', v::email()->setName('email'))
                ->key('phone', v::phone()))
            ->key('address', v::arrayType()
                ->key('street', v::stringType())
                ->key('city', v::stringType())
                ->key('country', v::stringType())
                ->key('postal_code', v::stringType())
                ->key('state', v::stringType()))
            ->key('date_of_birth', v::date('Y-m-d')->setName('Date of Birth'))
            ->key('gender', v::stringType())
            ->key('nationality', v::stringType())
            ->key('languages', v::stringType());

        $allErrors = [];
        $generalErrors = [];

        try {
            $validation->assert($data);
        } catch (ValidationException $e) {
            $allErrors = array_merge($allErrors, $e->getMessages());
        }

        if (isset($data['password'])) {
            $passwordMessages = $this->validatePassword($data['password']);
            if (!empty($passwordMessages)) {
                if (isset($passwordMessages['length'])) {
                    $allErrors['password'] = 'Password must be at least 8 characters long.';
                }
                if (!empty($passwordMessages['general'])) {
                    $generalErrors = array_merge($generalErrors, $passwordMessages['general']);
                }
            }
        }

        if (!empty($allErrors) || !empty($generalErrors)) {

            $allErrors['general'] = $generalErrors; 
            return $this->errorResponse($response, $allErrors, 400);
        }

        $request = $request->withParsedBody($data);
        return $handler->handle($request);
    }

    private function validatePassword($password): array {
        $messages = [];
        $messages['general'] = [];
        
        if (strlen($password) < 8) {
            $messages['length'] = 'The password must be at least 8 characters long.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $messages['general'][] = 'The password must contain at least one uppercase letter.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $messages['general'][] = 'The password must contain at least one lowercase letter.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $messages['general'][] = 'The password must contain at least one number.';
        }
        if (!preg_match('/[\W]/', $password)) {
            $messages['general'][] = 'The password must contain at least one special character.';
        }

        return $messages;
    }

    private function errorResponse(Response $response, array $errors, $status): Response {
        $formattedErrors = $this->formatErrors($errors);

        $response->getBody()->write(json_encode([
            'message' => 'There were validation errors',
            'data' => [
                'errors' => $formattedErrors
            ]
        ]));

        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }

    private function formatErrors(array $errors): array {
        $formattedErrors = [];

        foreach ($errors as $field => $messages) {
            if (is_array($messages)) {
                $formattedErrors[$field] = $messages; 
            } else {
                $formattedErrors[$field] = $messages;
            }
        }

        return $formattedErrors;
    }
}
