<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Profile;
use App\Repository\EventRepository;
use App\Repository\ProfileRepository;
use App\Repository\VenueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

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

    #[Route("/show/{id}", name: "app_event_show")]
    public function showOne(Event $event):Response{
        $response = [
            "content"=>$event,
            "code"=>200
        ];
        return $this->json($response);
    }


    #[Route("/create",name:"app_event_create")]
    public function createEvent(
        ProfileRepository $profileRepository,
        VenueRepository $venueRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        Request $request
    ):Response{
        $newEvent = $serializer->deserialize($request->getContent(),Event::class, "json");
        if ($this->getUser()->getProfile()->getRole()!="organizer"){
            return $this->json("You're not organizer");
        }
        $parameters = json_decode($request->getContent(), true);

        $newEvent->setOwner($this->getUser()->getProfile());
        foreach ($parameters["artists"] as $artistId){
            $artist = $profileRepository->find($artistId);
            if ($artist->getRole()=="artist" or $artist->getRole()=="Artist"){
                $newEvent->addArtist($artist);
            }
        }
        foreach ($newEvent->getArtists() as $artist){
            if ($artist->getId() == null){
                $newEvent->removeArtist($artist);
            }
        }
        $venueId = $parameters["venue"];
        $newEvent->setVenue($venueRepository->find($venueId));
        $manager->persist($newEvent);
        $manager->flush();

        return $this->json($newEvent,200,[],["groups"=>"event"]);
    }
}
