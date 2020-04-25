<?php

namespace App\Command;

use App\Entity\Dmc\MedicalChit;
use App\Message\Dmc\AssociateMerchant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class MerchantAssignmentInitCommand extends Command
{
    protected static $defaultName = 'merchant:assignment:init';

    private $bus, $registry;

    public function __construct(ManagerRegistry $registry, MessageBusInterface $bus, string $name = null)
    {
        parent::__construct($name);
        $this->registry = $registry;
        $this->bus = $bus;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
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

        $dmcs = $this->registry->getRepository(MedicalChit::class)->findBy(['merchantAssignmentsInit' => false]);
        $manager = $this->registry->getManager();

        /** @var MedicalChit $medicalChit */
        foreach ($dmcs as $medicalChit) {
            $associateMerchant = new AssociateMerchant();
            $associateMerchant->dmcUuid = $medicalChit->getUuid();
            try {
                $this->bus->dispatch($associateMerchant);
                $medicalChit->setMerchantAssignmentsInit(true);
                $manager->flush();
            } catch (\Throwable $exception) {
                throw $exception;
            }
        }

        return 0;
    }
}
