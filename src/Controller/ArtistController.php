<?php

namespace App\Controller;

use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/artist")]
class ArtistController extends AbstractController
{
    #[Route('/index', name: 'app_artist')]
    public function index(ProfileRepository $repository,EntityManagerInterface $manager): Response
    {

       $artists = $repository->findBy(["role"=>"artist"]);
       $response = [
           "content"=>$artists,
           "code"=>200
       ];
       return $this->json($response,200,[],["groups"=>"artist"]);
    }
}
