<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rafael\SubwayRoutesApi\Database\Entity\User;
use Rafael\SubwayRoutesApi\Database\Database;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Ramsey\Uuid\Uuid;
use Rafael\SubwayRoutesApi\Middleware\UserValidationMiddleware;
use Rafael\SubwayRoutesApi\DTO\UserDTO;

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
            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $response->getBody()->write(json_encode(['message' => 'User created successfully']));
                return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode(['message' => 'Failed to create user', 'details' => $e->getMessage()]));
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            }
        })->add(new UserValidationMiddleware());
        $group->get('', function (Request $request, Response $response) use ($entityManager) {
            $queryParams = $request->getQueryParams();
            $skip = isset($queryParams['skip']) ? (int) $queryParams['skip'] : 0;
            $limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : 10;
            $sort = $queryParams['sort'] ?? 'username';
            $order = $queryParams['order'] ?? 'asc';
        
            $qb = $entityManager->getRepository(User::class)->createQueryBuilder('u');
        
            if (!empty($queryParams['username'])) {
                $qb->andWhere($qb->expr()->like('u.username', ':username'))
                   ->setParameter('username', '%' . $queryParams['username'] . '%');
            }
            if (!empty($queryParams['email'])) {
                $qb->andWhere($qb->expr()->like('u.email', ':email'))
                   ->setParameter('email', '%' . $queryParams['email'] . '%');
            }
            if (!empty($queryParams['phone'])) {
                $qb->andWhere($qb->expr()->like('u.phone', ':phone'))
                   ->setParameter('phone', '%' . $queryParams['phone'] . '%');
            }
            if (!empty($queryParams['date_of_birth'])) {
                $qb->andWhere('u.date_of_birth = :date_of_birth')
                   ->setParameter('date_of_birth', $queryParams['date_of_birth']);
            }
            if (!empty($queryParams['gender'])) {
                $qb->andWhere('u.gender = :gender')
                   ->setParameter('gender', $queryParams['gender']);
            }
            if (!empty($queryParams['nationality'])) {
                $qb->andWhere('u.nationality = :nationality')
                   ->setParameter('nationality', $queryParams['nationality']);
            }
            if (!empty($queryParams['languages'])) {
                $qb->andWhere($qb->expr()->like('u.languages', ':languages'))
                   ->setParameter('languages', '%' . $queryParams['languages'] . '%');
            }
            if (!empty($queryParams['street'])) {
                $qb->andWhere($qb->expr()->like('u.street', ':street'))
                   ->setParameter('street', '%' . $queryParams['street'] . '%');
            }
            if (!empty($queryParams['city'])) {
                $qb->andWhere($qb->expr()->like('u.city', ':city'))
                   ->setParameter('city', '%' . $queryParams['city'] . '%');
            }
            if (!empty($queryParams['state'])) {
                $qb->andWhere($qb->expr()->like('u.state', ':state'))
                   ->setParameter('state', '%' . $queryParams['state'] . '%');
            }
            if (!empty($queryParams['country'])) {
                $qb->andWhere($qb->expr()->like('u.country', ':country'))
                   ->setParameter('country', '%' . $queryParams['country'] . '%');
            }
            if (!empty($queryParams['postal_code'])) {
                $qb->andWhere('u.postal_code = :postal_code')
                   ->setParameter('postal_code', $queryParams['postal_code']);
            }
        
            if (in_array($sort, ['username', 'date_of_birth', 'created_at'])) {
                $qb->orderBy('u.' . $sort, $order === 'desc' ? 'DESC' : 'ASC');
            }
            $qb->setFirstResult($skip)
               ->setMaxResults($limit);
        
            $users = $qb->getQuery()->getResult();
            $userData = array_map(fn($user) => (new UserDTO($user))->toArray(), $users);
        
            $countQueryBuilder = $entityManager->getRepository(User::class)->createQueryBuilder('u')
                ->select('COUNT(u.id)');
        
            if (!empty($queryParams['username'])) {
                $countQueryBuilder->andWhere($countQueryBuilder->expr()->like('u.username', ':username'))
                                  ->setParameter('username', '%' . $queryParams['username'] . '%');
            }
            if (!empty($queryParams['email'])) {
                $countQueryBuilder->andWhere($countQueryBuilder->expr()->like('u.email', ':email'))
                                  ->setParameter('email', '%' . $queryParams['email'] . '%');
            }
            if (!empty($queryParams['phone'])) {
                $countQueryBuilder->andWhere($countQueryBuilder->expr()->like('u.phone', ':phone'))
                                  ->setParameter('phone', '%' . $queryParams['phone'] . '%');
            }
            if (!empty($queryParams['date_of_birth'])) {
                $countQueryBuilder->andWhere('u.date_of_birth = :date_of_birth')
                                  ->setParameter('date_of_birth', $queryParams['date_of_birth']);
            }
            if (!empty($queryParams['gender'])) {
                $countQueryBuilder->andWhere('u.gender = :gender')
                                  ->setParameter('gender', $queryParams['gender']);
            }
            if (!empty($queryParams['nationality'])) {
                $countQueryBuilder->andWhere('u.nationality = :nationality')
                                  ->setParameter('nationality', $queryParams['nationality']);
            }
            if (!empty($queryParams['languages'])) {
                $countQueryBuilder->andWhere($countQueryBuilder->expr()->like('u.languages', ':languages'))
                                  ->setParameter('languages', '%' . $queryParams['languages'] . '%');
            }
            if (!empty($queryParams['street'])) {
                $countQueryBuilder->andWhere($countQueryBuilder->expr()->like('u.street', ':street'))
                                  ->setParameter('street', '%' . $queryParams['street'] . '%');
            }
            if (!empty($queryParams['city'])) {
                $countQueryBuilder->andWhere($countQueryBuilder->expr()->like('u.city', ':city'))
                                  ->setParameter('city', '%' . $queryParams['city'] . '%');
            }
            if (!empty($queryParams['state'])) {
                $countQueryBuilder->andWhere($countQueryBuilder->expr()->like('u.state', ':state'))
                                  ->setParameter('state', '%' . $queryParams['state'] . '%');
            }
            if (!empty($queryParams['country'])) {
                $countQueryBuilder->andWhere($countQueryBuilder->expr()->like('u.country', ':country'))
                                  ->setParameter('country', '%' . $queryParams['country'] . '%');
            }
            if (!empty($queryParams['postal_code'])) {
                $countQueryBuilder->andWhere('u.postal_code = :postal_code')
                                  ->setParameter('postal_code', $queryParams['postal_code']);
            }
        
            $totalUsers = $countQueryBuilder->getQuery()->getSingleScalarResult();
        
            $responseData = [
                'message' => empty($userData) ? 'No users found.' : 'Users found.',
                'data' => $userData,
                'totalNumber' => $totalUsers
            ];
        
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        });
    });
};
