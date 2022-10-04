<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ProduitController extends AbstractController
{
    public function __invoke(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $produit = $request->get('data');
        $produit->setUuid(Uuid::v1());

        $em = $doctrine->getManager();
        $em->persist($produit);
        $em->flush();

        return new JsonResponse(['status' => 'OKKKKKK']);
    }
}
