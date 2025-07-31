<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// ...

#[AsCommand(name: 'app:create-admin')]
class CreateAdminCommand extends Command
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        
        // Utilisez $this->passwordHasher
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'MotDePasse123!'
        );
        $admin->setPassword($hashedPassword);

        $this->em->persist($admin);
        $this->em->flush();

        $output->writeln('✅ Admin créé avec succès !');
        return Command::SUCCESS;
    }
}