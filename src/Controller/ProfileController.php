<?php

namespace App\Controller;

use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/profile")]
class ProfileController extends AbstractController
{
    #[Route('/getActual', name: 'app_profile_actual')]
    public function getActual(UserRepository $repository): Response
    {
        $actualUser = $repository->findOneBy(["email"=>$this->getUser()->getEmail()]);

            $response = [
                "content"=>$actualUser,
                "code"=>200
            ];
            return $this->json($response,200,[],["groups"=>"user"]);
    }
}
