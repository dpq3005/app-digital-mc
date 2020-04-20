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

//    protected function handleThrowable(\Throwable $exception, $context, $message, MedicalChit $medicalChit = null, $responseParams = [])
//    {
//
//        $loggingManager = $this->registry->getManager('logging');
//        $loggingManager->clear();
//
//        $reflection = new \ReflectionClass($this);
//        $shortName = $reflection->getShortName();
//
//        $this->logger->critical($exception->getMessage().' '.$exception->getTraceAsString(), [$shortName, 'Catch'.$context]);
//
//        /** @var WorkerRequestLog $wrl */
//        $wrl = $loggingManager->getRepository(WorkerRequestLog::class)->findOneByUuid($message->workerRequestUuid);
//        $wrl->setState(WorkerRequestLog::STATE_FAILED);
//
//        if (array_key_exists('code', $responseParams)) {
//            $errorCode = $responseParams['code'];
//        } elseif (empty($errorCode = $exception->getCode())) {
//            $errorCode = 400;
//        };
//
//        $wrl->responseCode = $errorCode;
//
//        $wrl->responseMessage = [
//            'objectUuid' => $medicalChit ? $medicalChit->getUuid() : null,
//            'code' => $errorCode,
//            'file' => $exception->getFile(),
//            'line' => $exception->getLine(),
//
//            'message' => $exception->getMessage(),
//            'trace' => $exception->getTrace()
//        ];
//
//        $loggingManager->flush();
//    }
}