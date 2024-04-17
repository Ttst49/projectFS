<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/ticket")]
class TicketController extends AbstractController
{
    #[Route('/index', name: 'app_ticket')]
    public function index(TicketRepository $repository): Response
    {
        $tickets = $repository->findBy(["owner"=>$this->getUser()->getProfile()]);
        $response = [
            "content"=>$tickets,
            "code"=>200
        ];
        return $this->json($response);
    }

    #[Route("/show/{id}")]
    public function showOne(Ticket $ticket):Response{
        $response = [
            "content"=>$ticket,
            "code"=>200
        ];
        return $this->json($response);
    }
}
