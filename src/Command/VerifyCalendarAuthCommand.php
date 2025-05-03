<?php
// src/Command/VerifyCalendarAuthCommand.php
namespace App\Command;

use App\Service\GoogleCalendarService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VerifyCalendarAuthCommand extends Command
{
    protected static $defaultName = 'app:verify-calendar-auth';
    private GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        parent::__construct();
        $this->calendarService = $calendarService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $calendar = new \Google\Service\Calendar($this->calendarService->getClient());
        
        try {
            $calendar->calendars->get('primary');
            $output->writeln('<info>Authentication successful!</info>');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Authentication failed:</error>');
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }
    }
}