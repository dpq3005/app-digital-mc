<?php

namespace App\OpenApi;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MerchantProductUuidDecorator implements NormalizerInterface
{
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $path = '/products/{id}/find-merchants-by-product-uuid';

        // e.g. add a custom parameter
        $docs['paths'][$path]['get']['parameters'][] = [
            'name' => 'id',
            'description' => 'Product Uuidd',
            'default' => 'id',
            'in' => 'path',
        ];

        // e.g. remove an existing parameter
        $docs['paths'][$path]['get']['parameters'] = array_values(array_filter($docs['paths'][$path]['get']['parameters'], function ($param) {
            return true;
        }));

        // Override title
//        $docs['info']['title'] = 'Beneficiaries Api';

        return $docs;
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}