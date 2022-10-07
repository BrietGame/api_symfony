<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class ApiRegisterController extends AbstractController
{
    #[Route('/api/register', name: 'app_api_register', methods: ['POST'])]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = new User();
//        dd($request);
        $user->setEmail($request->get('email'));
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $request->get('password')
        );
        $user->setPassword($hashedPassword);
        $user->setUuid(Uuid::v1());
        $user->setRoles(['ROLE_USER']);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        // TODO: Création du panier de l'utilisateur
        $panier = new Panier();
        $panier->setUserId($user);
        $panier->setUuid(Uuid::v1());
        $entityManager->persist($panier);
        $entityManager->flush();

        return new JsonResponse($user);
    }
}
