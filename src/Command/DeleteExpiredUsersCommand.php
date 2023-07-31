<?php
// src/Command/DeleteExpiredUsersCommand.php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteExpiredUsersCommand extends Command
{
    protected static $defaultName = 'app:delete-expired-users';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Supprime les comptes dont le contrat est terminé (date de sortie dépassée)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $expiredUsers = $this->entityManager->getRepository(User::class)->findExpiredUsers();

        foreach ($expiredUsers as $user) {
            $this->entityManager->remove($user);
        }

        $this->entityManager->flush();

        $output->writeln(count($expiredUsers) . ' comptes d\'utilisateurs ont été supprimés.');

        return Command::SUCCESS;
    }
}

