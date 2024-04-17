<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('_profiler_exception');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('api/register', name: 'app_register_json')]
    public function registerJson(SerializerInterface $serializer,Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager): Response
    {

        $user = $serializer->deserialize($request->getContent(), User::class, "json");

        $parameters = json_decode($request->getContent(), true);


        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $parameters["password"]
            )
        );
        $profile = new Profile();
        $user->setProfile($profile);

        $manager->persist($user);
        $manager->flush();

        $response = [
            "content"=> "The user ".$user->getUsername()." has been created",
            "status"=>201,
            "user"=>$user
        ];

        return $this->json($response, 201, [], ["groups" => "forUserIndexing"]);

    }
}
