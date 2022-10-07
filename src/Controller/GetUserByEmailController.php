<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetUserByEmailController extends AbstractController
{
    public function __invoke(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);
        return new JsonResponse($user);
    }
}
