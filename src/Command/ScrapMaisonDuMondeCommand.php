<?php

// src/Command/ScrapMaisonDuMondeCommand.php
namespace App\Command;

use App\Service\MaisonDuMondeCrawlerManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapMaisonDuMondeCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'scrap-mdm';
    protected MaisonDuMondeCrawlerManager $mdmCrawlerManager;

    public function __construct(MaisonDuMondeCrawlerManager $mdmCrawlerManager)
    {
        $this->mdmCrawlerManager = $mdmCrawlerManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command start the scrapping of Maison du Monde website.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln([
                '',
                'Scrapper \'Maison du Monde\'',
                '',
                '============',
                '',
            ]);
            $this->mdmCrawlerManager->main();

            return Command::SUCCESS;
        } catch (Exception $exception) {
            echo $exception;
            return Command::FAILURE;
        }
    }
}
