<?php

namespace App\Command;

use App\Entity\Dmc\MedicalChit;
use App\Entity\Dmc\MerchantAssignment;
use App\Entity\Worker\AdminNotifier;
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

        $manager = $this->registry->getManager();

        // disable expired workers first
        $notifiers = $this->registry->getRepository(AdminNotifier::class)->findBy(['enabled' => true,
        ]);

        $now = new \DateTime();
        $pid = getmypid();

        $expiredNotifiers = [];
        $newNotifier = null;

        if (empty($notifiers)) {
            $newNotifier = new AdminNotifier();
        }

        /** @var AdminNotifier $notifier */
        foreach ($notifiers as $notifier) {
            $interval = $notifier->getCreatedAt()->diff($now);
            $diff = (int) $interval->format('%i');
            if ($diff > 1) {
                $expiredNotifiers[] = $notifier;
                if ($newNotifier === null) {
                    $newNotifier = new AdminNotifier();
                }
            } else {
                $newNotifier = false;
            }
        }

        /** @var AdminNotifier $notifier */
        foreach ($expiredNotifiers as $notifier) {
            posix_kill($notifier->getPid());
            $notifier->setEnabled(false);
            $manager->persist($notifier);
        }

        // create new notif
        if (empty($newNotifier)) {
            return 0;
        }

        $newNotifier->setPid($pid);
        $newNotifier->setEnabled(true);
        $manager->persist($newNotifier);
        $manager->flush();

        $manager->clear();

        // Check if we are the only one
        $notifiers = $this->registry->getRepository(AdminNotifier::class)->findBy(['enabled' => true,
        ]);
        /** @var AdminNotifier $notifier */
        foreach ($notifiers as $notifier) {
            if ($notifier->getId() > $newNotifier->getId()) {
                /** @var AdminNotifier $obsoleteNotifier */
                $obsoleteNotifier = $this->registry->getRepository(AdminNotifier::class)->find($newNotifier->getId());
                $obsoleteNotifier->setEnabled(false);
                $manager->persist($obsoleteNotifier);
                $manager->flush();
                return 0;
            }
        }

        $dmcs = $this->registry->getRepository(MedicalChit::class)->findBy([
            'adminNotified' => false,
            'merchantAssignmentsInit' => true
        ]);

        /** @var MedicalChit $dmc */
        foreach ($dmcs as $dmc) {
            $html = '';
            $html .= '<p><strong>Client Name:</strong> '.$dmc->getBenefitProvider()->getName().'</p>';
            $html .= '<p><strong>Worker Name:</strong> '.$dmc->getBeneficiaryName().'</p>';
            $html .= '<p><strong>Product Name:</strong> '.$dmc->getProductName().'</p>';

            if (empty($dmc->getMerchantUuids())) {
                $html .= '<p><strong>All applicable merchants</strong></p>';
            } else {
                $html .= '<ol><strong>Selected Merchants:</strong>';
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
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('New DMC created')
                ->html($html);

            if ($cc = getenv('ADMIN_CC')) {
                $email->cc($cc);
            }

            try {
                $this->mailer->send($email);
                $dmc->setAdminNotified(true);
                $manager->persist($dmc);
                $manager->flush();
            } catch (\Throwable $e) {
                throw $e;
            }
        }

        /** @var AdminNotifier $doneNotifier */
        $doneNotifier = $this->registry->getRepository(AdminNotifier::class)->find($newNotifier->getId());
        $doneNotifier->setEnabled(false);
        $manager->flush();

        $disabledNotifiers = $this->registry->getRepository(AdminNotifier::class)->findByEnabled(false);
        foreach ($disabledNotifiers as $notifier) {
            $manager->remove($notifier);
        }

        $manager->flush();

        return 0;
    }
}
