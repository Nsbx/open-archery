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
use Symfony\Component\Uid\UuidV4;

#[AsCommand(
    name: 'app:user:generate-secret',
    description: 'Generate a secret for a user.',
)]
class UserGenerateSecretCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $repository = $this->entityManager->getRepository(User::class);

        $users = $repository->findAll();

        foreach ($users as $user) {
            $user->setSecret(new UuidV4());
        }

        $this->entityManager->flush();

        $io->success('Secret generated successfully.');

        return Command::SUCCESS;
    }
}
