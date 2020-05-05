<?php

namespace App\Command;

use App\Entity\Dmc\MedicalChit;
use App\Entity\Dmc\MerchantAssignment;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AdminNotifyCommand extends Command
{
    protected static $defaultName = 'admin:notify';

    private $registry, $mailer;

    public function __construct(MailerInterface $mailer, ManagerRegistry $registry, string $name = null)
    {
        parent::__construct($name);
        $this->registry = $registry;
        $this->mailer = $mailer;
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

        $dmcs = $this->registry->getRepository(MedicalChit::class)->findBy([
            'adminNotified' => false,
            'merchantAssignmentsInit' => true
        ]);

        $manager = $this->registry->getManager();

        /** @var MedicalChit $dmc */
        foreach ($dmcs as $dmc) {
            $html = '';
            $html .= '<p><strong>Client name:</strong> '.$dmc->getBenefitProvider()->getName().'</p>';
            $html .= '<p><strong>Worker name:</strong> '.$dmc->getBeneficiaryName().'</p>';

            if (empty($dmc->getMerchantUuids())) {
                $html .= '<p><strong>All applicable merchants</strong></p>';
            } else {
                $html .= '<ol>';
                $mas = $dmc->getMerchantAssignments();
                /** @var MerchantAssignment $ma */
                foreach ($mas as $ma) {
                    $html .= '<li>'.$ma->getMerchant()->getName().'</li>';
                }
                $html .= '</ol>';
            }

            $email = (new Email())
                ->from('no-reply@magenta-wellness.com')
                ->to('sam@magenta-wellness.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('New DMC created')
                ->html($html);

            $this->mailer->send($email);
            try {
                $dmc->setAdminNotified(true);
                $manager->persist($dmc);
            } catch (\Throwable $e) {
                throw $e;
            }
        }

        $manager->flush();

        return 0;
    }
}
