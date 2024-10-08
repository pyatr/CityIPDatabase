<?php

namespace App\Command;

use App\Controller\IPLocationController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'convert-ips-to-int')]
class ConvertIPsToInt extends Command
{
    public function __construct(private IPLocationController $ipLocationController)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->ipLocationController->updateAllIPsInt();

        return Command::SUCCESS;
    }
}