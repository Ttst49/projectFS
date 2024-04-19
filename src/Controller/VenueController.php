<?php

namespace App\Controller;

use App\Entity\Venue;
use App\Repository\VenueRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api/venue")]
class VenueController extends AbstractController
{
    #[Route('/all', name: 'app_venue_all')]
    public function getAll(VenueRepository $repository): Response
    {
        $response = [
            "content"=>$repository->findAll(),
            "code"=>200
        ];
        return $this->json($response,200,[],["groups"=>"venue"]);
    }



    #[Route('/index', name: 'app_venue_index')]
    public function index(VenueRepository $repository): Response
    {
        $response = [
            "content"=>$repository->findBy(["owner"=>$this->getUser()->getProfile()]),
            "code"=>200
        ];
        return $this->json($response,200,[],["groups"=>"venue"]);
    }

    #[Route("/show/{id}",name: "app_venue_show")]
    public function showOne(Venue $venue):Response{
        $response = [
            "content"=>$venue,
            "code"=>200
        ];
        return $this->json($response,200,[],["groups"=>"venue"]);
    }

    #[Route("/create", name: "app_venue_create")]
    public function createOne(SerializerInterface $serializer,Request $request, EntityManagerInterface $manager):Response{
        $newVenue = $serializer->deserialize($request->getContent(),Venue::class,"json");
        $newVenue->setOwner($this->getUser()->getProfile());
        $manager->persist($newVenue);
        $manager->flush();
        $response = [
            "content" => $newVenue,
            "code"=>201
        ];
        return $this->json($response,200,[],["groups"=>"venue"]);
    }


    #[Route("/edit/{id}",name: "venue_edit")]
    public function editOne(Venue $venue,SerializerInterface $serializer, Request $request, EntityManagerInterface $manager):Response{
        $editedVenue = $serializer->deserialize($request->getContent(),Venue::class,"json",["object_to_populate"=>$venue]);
        $editedVenue->setCreatedAt(new \DateTime());
        $manager->persist($editedVenue);
        $manager->flush();
        $response = [
            "content" => $editedVenue,
            "code"=>201
        ];
        return $this->json($response,200,[],["groups"=>"venue"]);
    }

    #[Route("/remove/{id}",name: "venue_remove")]
    public function removeOne(Venue $venue, EntityManagerInterface $manager):Response{

        $manager->remove($venue);
        $manager->flush();
        $response = [
            "content" => "you successfully removed the venue",
            "code"=>200
        ];
        return $this->json($response);
    }
}
