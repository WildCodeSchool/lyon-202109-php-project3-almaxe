<?php

namespace App\Command;

use Exception;
use App\Service\FromAmazon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AmazonCommand extends Command
{
    private FromAmazon $fromAmazon;

    protected static $defaultName = 'get:amazon';

    public function __construct(FromAmazon $fromAmazon)
    {
        parent::__construct();
        $this->fromAmazon = $fromAmazon;
    }

    public function configure(): void
    {
        $this
            ->setHelp('Take data from amazon with RainForest API');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln([
                '',
                '=========================',
                'API - RAINFOREST - AMAZON',
                '=========================',
                '',
            ]);
            $this->fromAmazon->main();
            return Command::SUCCESS;
        } catch (Exception $exception) {
            echo ("We find a error with : $exception");
            return Command::FAILURE;
        }
    }
}
