<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class UpdatePanierByUserController extends AbstractController
{
    public function __invoke(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $idUser = $request->get('userId');
        $panier = $doctrine->getRepository(Panier::class)->findOneBy(['userId' => $idUser]);
        $panier->addProduit($request->get('produits'));

        $em = $doctrine->getManager();
        $em->persist($panier);
        $em->flush();

        return new JsonResponse($panier);
    }
}
