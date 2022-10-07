<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class AddPanierByUserController extends AbstractController
{
    public function __invoke(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $idUser = 2;
        $panier = new Panier();
        $user = $doctrine->getRepository(User::class)->find($idUser);
        $panier->setUserId($user);
        $panier->setUuid(Uuid::v1());

        $em = $doctrine->getManager();
        $em->persist($panier);
        $em->flush();

        return new JsonResponse($panier);
    }
}
