<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Uid\Uuid;

class RegistrationController extends AbstractController
{
//    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
//    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): JsonResponse
//    {
//        $user = new User();
//        $form = $this->createForm(RegistrationFormType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $user->setEmail($request->get('email'));
//            // encode the plain password
//            $user->setPassword(
//                $userPasswordHasher->hashPassword(
//                    $user,
//                    $form->get('plainPassword')->getData()
//                )
//            );
//            $user->setUuid(Uuid::v1());
//
//            $entityManager->persist($user);
//            $entityManager->flush();
//            // do anything else you need here, like send an email
//
//            // TODO: Création du panier de l'utilisateur
//            $panier = new Panier();
//            $panier->setUserId($user);
//            $panier->setUuid(Uuid::v1());
//            $entityManager->persist($panier);
//            $entityManager->flush();
//
//            return $userAuthenticator->authenticateUser(
//                $user,
//                $authenticator,
//                $request
//            );
//        }
//
//        return new JsonResponse(['status' => 'success']);
//    }
}
