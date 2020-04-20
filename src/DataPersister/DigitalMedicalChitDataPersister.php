<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\DataProvider\Invoicing\InvoicePaymentProofGetter;
use App\Dto\Dmc\DigitalMedicalChit;
use App\Dto\Invoicing\Invoice;
use App\Dto\Invoicing\InvoicePayment;
use App\Dto\Invoicing\InvoicePaymentProof;
use App\Entity\Dmc\MedicalChit;
use App\Message\Dmc\CreateDmc;
use App\Message\Dmc\InvoiceRequest;
use App\Service\Aws\AwsS3Service;
use App\Service\HttpService;
use App\Service\ThingService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

class DigitalMedicalChitDataPersister implements ContextAwareDataPersisterInterface
{
    protected $registry, $bus, $http;

    public function __construct(MessageBusInterface $bus, ManagerRegistry $registry, HttpService $http)
    {
        $this->bus = $bus;
        $this->registry = $registry;
        $this->http = $http;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof DigitalMedicalChit;
    }

    /**
     * @param DigitalMedicalChit $data
     * @param array $context
     * @return object|void|null
     */
    public function persist($data, array $context = [])
    {
        if (empty($data)) {
            throw new NotFoundHttpException('Benefit Provider not found');
        }

        if (empty($data->getUuid())) {
            /** @var CreateDmc $message */
            $message = new CreateDmc();
        } else {

        }

        if (empty($productUuid = $data->getProduct())) {
            throw new \InvalidArgumentException('Empty Product');
        }

        $res = $this->http->get('products', $productUuid, false, 'product');

        $product = $res['body'];
        if (empty($product)) {
            throw new NotFoundHttpException('Product not found for ID: '.$productUuid);
        }

        $message->productUuid = $productUuid;
        $message->productName = $product->name;
        $message->beneficiaryName = $data->getBeneficiaryName();
        $message->beneficiaryNric = $data->getBeneficiaryNric();
        $message->merchantUuids = $data->getMerchants();

        $message->isEventSourcingEnabled = true;
        $this->bus->dispatch($message);

        $data->setUuid($message->uuid);

        return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->invoiceAction = 'DELETE';
//        $this->process($data, $context);
        header('worker-request-uuid: '.$data->workerRequestUuid);
    }

}