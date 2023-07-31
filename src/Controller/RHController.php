<?php
// src/Controller/RHController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RHController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/rh', name: 'rh_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_default'); // Rediriger vers la page d'accueil ou la page de connexion
        }

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('rh/dashboard.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/rh/register', name: 'rh_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier le type de contrat et la date de sortie
            if ($user->getTypeContrat() === 'CDI') {
                $user->setDateSortie(null); // Réinitialiser la date de sortie pour un contrat CDI
            }

            // Encode le mot de passe avant de l'enregistrer
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $user->getPassword())
            );

            // Assigne le rôle ROLE_USER à l'utilisateur
            $user->setRoles(['ROLE_USER']);

            // Enregistre l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('rh_dashboard');
        }

        return $this->render('rh/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/rh/add-user', name: 'add_user')]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier le type de contrat et la date de sortie
            if ($user->getTypeContrat() === 'CDI') {
                $user->setDateSortie(null); // Réinitialiser la date de sortie pour un contrat CDI
            }

            // Encodez le mot de passe avant de l'enregistrer
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $user->getPassword())
            );

            // Enregistrez l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirigez vers le tableau de bord ou toute autre page après la création réussie de l'utilisateur
            return $this->redirectToRoute('rh_dashboard');
        }

        return $this->render('rh/add_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


