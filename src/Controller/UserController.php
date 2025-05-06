<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('/users/{page}', name: 'user_index', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function index(Request $request, int $page): Response
    {
       $pagination = $this->userService->getPagination($page);

        if ($request->isXmlHttpRequest()) {
            return $this->render('user/_user_list.html.twig', [
                'users' => $pagination->getUsers(),
            ]);
        }

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'page' => $page,
        ]);
    }

    #[Route('/users/create', name: 'user_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('user/create.html.twig');
    }
}
