<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:create',
    description: 'Create a new user',
)]
class UserCreateCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('firstname', InputArgument::REQUIRED)
            ->addArgument('lastname', InputArgument::REQUIRED)
            ->addArgument('level', InputArgument::REQUIRED)
            ->addArgument('experienceYears', InputArgument::REQUIRED)
            ->addArgument('registrationYear', InputArgument::REQUIRED)
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('isAdmin', InputArgument::OPTIONAL, '', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $firstname        = $input->getArgument('firstname');
        $lastname         = $input->getArgument('lastname');
        $level            = $input->getArgument('level');
        $experienceYears  = $input->getArgument('experienceYears');
        $registrationYear = $input->getArgument('registrationYear');
        $email            = $input->getArgument('email');
        $isAdmin          = $input->getArgument('isAdmin');

        $user = (new User())
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setLevel($level)
            ->setExperienceYears($experienceYears)
            ->setRegistrationYear($registrationYear)
            ->setEmail($email)
        ;

        if ($isAdmin) {
            $user->setRoles(['ROLE_ADMIN']);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('New user created successfully.');

        return Command::SUCCESS;
    }
}
