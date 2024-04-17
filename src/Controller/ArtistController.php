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
    #[Route('/index', name: 'app_artist_index')]
    public function index(ProfileRepository $repository): Response
    {

       $artists = $repository->findBy(["role"=>"artist"]);
       $response = [
           "content"=>$artists,
           "code"=>200
       ];
       return $this->json($response,200,[],["groups"=>"artist"]);
    }


    #[Route('/show/{id}',name: "app_artist_show")]
    public function showOne(ProfileRepository $repository, $id):Response{
        $artist = $repository->findBy(["id"=>$id]);
        $response = [
            "content"=>$artist,
            "code"=>200
        ];
        return $this->json($response);
    }
}
