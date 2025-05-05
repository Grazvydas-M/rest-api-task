<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    #[Route('/users/{page}', name: 'user_index', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(Request $request, int $page): Response
    {
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $users = $this->userRepository->findBy([], null, $limit, $offset);
        $totalUsers = $this->userRepository->count();
        $hasMore = ($page * $limit) < $totalUsers;

        if ($request->isXmlHttpRequest()) {
            return $this->render('user/_user_list.html.twig', [
                'users' => $users,
            ]);
        }

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'page' => $page,
            'hasMore' => $hasMore,
        ]);
    }

    #[Route('/users/create', name: 'user_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('user/create.html.twig');
    }
}
