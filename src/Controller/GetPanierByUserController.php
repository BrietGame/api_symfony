<?php

namespace App\Controller;

use App\Entity\Panier;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class GetPanierByUserController extends AbstractController
{
    public function __invoke(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $panier = $doctrine->getRepository(Panier::class)->findOneBy(['userId' => $request->get('userId')]);
        return new JsonResponse($panier);
    }
}
