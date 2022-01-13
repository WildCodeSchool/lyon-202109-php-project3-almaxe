<?php

namespace App\Command;

use App\Service\Home24CrawlerManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Home24ScrapCommand extends Command
{
    protected static $defaultName = 'app:git scrap-home';
    protected Home24CrawlerManager $home24Crawler;

    public function __construct(Home24CrawlerManager $home24Crawler)
    {
        $this->home24Crawler = $home24Crawler;

        parent::__construct();
    }

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln([
                '',
                'Scrapper \'Home24\'',
                '',
                '====================',
                '',
            ]);
            $this->home24Crawler->main();
            return Command::SUCCESS;
        } catch (Exception $exception) {
            echo $exception;
            return Command::FAILURE;
        }
    }
}
