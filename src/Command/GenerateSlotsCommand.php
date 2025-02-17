<?php

namespace App\Command;

use App\Repository\RecurringSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-slots',
    description: 'Generate instance slots from recurring slots.',
)]
class GenerateSlotsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RecurringSlotRepository $recurringSlotRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('weekNumber', InputArgument::OPTIONAL, 'Number of the week', (int) date('W'))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $weekNumber = $input->getArgument('weekNumber');

        $recurringSlots = $this->recurringSlotRepository->findBy(['enabled' => true]);

        foreach ($recurringSlots as $recurringSlot) {
            $slotInstance = $recurringSlot->generateSlotInstance($weekNumber);

            $this->entityManager->persist($slotInstance);
        }

        $this->entityManager->flush();

        $io->success('Slots generated successfully.');

        return Command::SUCCESS;
    }
}
