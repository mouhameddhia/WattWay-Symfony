<?php

namespace App\Command;

use App\Entity\Submission;
use App\Service\ML\PriorityScoringService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:train-priority-model',
    description: 'Train the priority scoring model with existing submissions'
)]
class TrainPriorityModelCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PriorityScoringService $priorityScoringService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Get all submissions
        $submissions = $this->entityManager->getRepository(Submission::class)->findAll();

        if (empty($submissions)) {
            $io->error('No submissions found to train the model.');
            return Command::FAILURE;
        }

        $io->info(sprintf('Training model with %d submissions...', count($submissions)));

        try {
            $this->priorityScoringService->trainModel($submissions);
            $io->success('Model trained successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error training model: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 