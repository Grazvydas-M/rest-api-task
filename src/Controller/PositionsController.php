<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PositionsController extends AbstractController
{
    public function __construct(
        private readonly PositionRepository  $positionRepository,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Route('/api/positions', name: 'positions_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $positions = $this->positionRepository->findAll();

        return new JsonResponse($this->serializer->serialize($positions, 'json'), 200, [], true);
    }
}
