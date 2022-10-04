<?php

namespace App\Controller;

use App\Entity\Panier;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class PanierController extends AbstractController
{
    public function __invoke(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $panier = $request->get('data');
        $panier->setUuid(Uuid::v1());

        $em = $doctrine->getManager();
        $em->persist($panier);
        $em->flush();

        return new JsonResponse(['status' => 'OKKKKKK']);
    }
}
