<?php

namespace App\Controller;

use App\Entity\Venue;
use App\Repository\VenueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/venue")]
class VenueController extends AbstractController
{
    #[Route('/index', name: 'app_venue_index')]
    public function index(VenueRepository $repository): Response
    {
        $response = [
            "content"=>$repository->findAll(),
            "code"=>200
        ];
        return $this->json($response);
    }

    #[Route("/show/{id}")]
    public function showOne(Venue $venue):Response{
        $response = [
            "content"=>$venue,
            "code"=>200
        ];
        return $this->json($response);
    }

    #[Route("/create")]
    public function createOne():Response{

    }
}
