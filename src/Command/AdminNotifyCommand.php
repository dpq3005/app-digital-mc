<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdminNotifyCommand extends Command
{
    protected static $defaultName = 'admin:notify';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        /**
         * [19:40, 03/05/2020] Kenneth Yap: Sorry, I just realised that for DMC, when a person creates a new dmc can u send sam@magenta-wellness.com the following
        [19:41, 03/05/2020] Kenneth Yap: Sub: New DMC created
        [19:41, 03/05/2020] Peter Bean: K
        [19:41, 03/05/2020] Kenneth Yap: Body: client name, worker name, and clinic(s)
         */

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
