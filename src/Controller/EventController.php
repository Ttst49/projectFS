<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/event")]
class EventController extends AbstractController
{
    #[Route('/index', name: 'app_event_index')]
    public function index(EventRepository $repository): Response
    {
        $response = [
            "content" => $repository->findAll(),
            "code"=>200,
        ];
        return $this->json($response);
    }

    #[Route("/show/{id}")]
    public function showOne(Event $event):Response{
        $response = [
            "content"=>$event,
            "code"=>200
        ];
        return $this->json($response);
    }
}
