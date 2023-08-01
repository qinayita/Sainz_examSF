<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository; // Assurez-vous d'importer le référentiel des utilisateurs

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer la liste de tous les utilisateurs
        $users = $userRepository->findAll();

        return $this->render('rh/dashboard.html.twig', [
            'users' => $users,
        ]);
    }
}

