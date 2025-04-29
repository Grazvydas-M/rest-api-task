<?php

namespace App\Controller;

use App\Dto\UserRegisterDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiUserController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface  $validator,
        private readonly UserRepository      $userRepository,
        private readonly UserService         $userService
    )
    {
    }

    #[Route('/api/users', name: 'api_users_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $userDto = $this->createUserDtoFromRequest($request);
        $errors = $this->validator->validate($userDto);

        if (count($errors) > 0) {
            $formattedErrors = $this->formatValidationErrors($errors);

            return new JsonResponse(['errors' => $formattedErrors], 400);
        }

        $user = $this->userService->createUser($userDto);

        return $this->json($user, Response::HTTP_CREATED);
    }

    #[Route('/api/users', name: 'api_users_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return $this->json($users, Response::HTTP_OK);
    }

    #[Route('/api/users/{id}', name: 'api_user_show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK);
    }

    private function formatValidationErrors($errors): array
    {
        $formattedErrors = [];

        foreach ($errors as $error) {
            /** @var ConstraintViolationInterface $error */
            $formattedErrors[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return $formattedErrors;
    }

    private function createUserDtoFromRequest(Request $request): UserRegisterDto
    {
        $rawData = $request->request->all();
        /** @var UserRegisterDto $userDto */
        $userDto = $this->serializer->deserialize(json_encode($rawData), UserRegisterDto::class, 'json');
        $userDto->setPhoto($request->files->get('photo'));

        return $userDto;
    }
}