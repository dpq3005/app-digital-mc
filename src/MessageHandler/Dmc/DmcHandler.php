<?php

namespace App\MessageHandler\Dmc;

use App\Dto\Dmc\DigitalMedicalChit;
use App\Entity\Dmc\MedicalChit;
use App\Message\AbstractMessage;
use App\Message\Dmc\AbstractDmc;
use App\Service\ThingService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class DmcHandler
{
    protected $registry, $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        $this->registry = $registry;
        $this->logger = $logger;
    }

    /**
     * @param AbstractDmc $message
     * @return MedicalChit
     */
    public function cast($message): MedicalChit
    {
        /** @var MedicalChit $medicalChit */
        $medicalChit = ThingService::cast($message, MedicalChit::class);
        if (!is_object($message)) {
            throw new \InvalidArgumentException('message should be an object');
        }

        return $medicalChit;
    }
}