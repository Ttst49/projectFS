<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api/donation")]
class DonationController extends AbstractController
{
    #[Route('/create/{id}', name: 'app_donation_create')]
    public function index(Profile $profile,
                          SerializerInterface $serializer,
                          EntityManagerInterface $manager,
                          Request $request
    ): Response{

        $response = [
            "content"=>"Vous ne pouvez donner qu'à un artiste",
            "code"=>200
        ];

        $newDonation = $serializer->deserialize($request->getContent(),Donation::class, "json");
        if ($profile->getRole() != "artist"){
            return $this->json($response);
        }
        if ($this->getUser()->getProfile()->getName()=="not defined yet"){
            $newDonation->setSpectatorName($this->getUser()->getEmail());
        }else{
            $newDonation->setSpectatorName($this->getUser()->getProfile()->getName()." ".$this->getUser()->getProfile()->getLastName());
        }
            $newDonation->setArtist($profile);
            $manager->persist($newDonation);
            $manager->flush();
            $response["content"]=$newDonation;

        return $this->json($response,200,[],["groups"=>"artist"]);
    }
}
