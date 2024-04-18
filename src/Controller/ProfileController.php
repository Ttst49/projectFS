<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Repository\EventRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api/profile")]
class ProfileController extends AbstractController
{
    #[Route('/getCurrent', name: 'app_profile_current')]
    public function getActual(UserRepository $repository): Response
    {
        $actualUser = $repository->findOneBy(["email"=>$this->getUser()->getEmail()]);

            $response = [
                "content"=>$actualUser,
                "code"=>200
            ];
            return $this->json($response,200,[],["groups"=>"user"]);
    }

    #[Route("/check/{eventId}/{profileId}",name: "app_profile_check")]
    public function checkIfSpectatorInEvent(
        $eventId,
        $profileId,
        EventRepository $eventRepository,
        ProfileRepository $profileRepository
    ):Response{
        $event = $eventRepository->find($eventId);
        $profile = $profileRepository->find($profileId);
        if ($event && $profile){
            foreach ($event->getSpectators() as $spectator){
                if ($spectator===$profile){
                    return $this->json("C'est OK");
                }
            }
            return $this->json("Pas autorisé",400);
        }
        return $this->json("L'événement ou l'utilisateur n'existe pas",200);
    }


    #[Route("/follow/{id}",name: "app_profile_follow")]
    public function followArtist(
        Profile $profile,
        EntityManagerInterface $manager,
        UserRepository $userRepository
    ):Response{
        $currentUser = $userRepository->findOneBy(["email"=>$this->getUser()->getUserIdentifier()]);
        $followedArtists = $currentUser->getProfile()->getFollowedArtists();
        foreach ($followedArtists as $artist){
            if ($artist === $profile){
                return $this->json("Vous suivez déjà cet artiste");
            }else{
                $currentUser->getProfile()->addFollowedArtist($profile);
                $manager->persist($currentUser);
                $manager->flush();
            }
        }
        return $this->json("Vous suivez désormais ".$profile->getName());
    }


    #[Route("/editCurrent",name: "app_profile_edit")]
    public function editProfile(
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        Request $request
    ):Response{
        $editedProfile = $serializer->deserialize(
            $request->getContent(),
            Profile::class,
            "json",
            ["object_to_populate"=>$this->getUser()->getProfile()]
        );
        $response = [
            "content"=>$editedProfile,
            "code"=>200
        ];

        return $this->json($response,200,[],["groups"=>"user"]);
    }
}
