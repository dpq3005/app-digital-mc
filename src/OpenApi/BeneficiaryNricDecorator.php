<?php

namespace App\OpenApi;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BeneficiaryNricDecorator implements NormalizerInterface
{
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        $customDefinition = [
            'name' => 'nric',
            'description' => 'get Benficiary with Nric from BenefitProvider Id',
            'default' => 'id',
            'in' => 'body', // body or query
        ];

        $path = '/benefit-providers/{id}/find-one-beneficiary-by-nric/{nric}';

        // e.g. add a custom parameter
        $docs['paths'][$path]['get']['parameters'][] = $customDefinition;
        $docs['paths'][$path]['get']['parameters'][] = [
            'name' => 'id',
            'description' => 'BenefitProvider Uuidd',
            'default' => 'id',
            'in' => 'body',
        ];

        // e.g. remove an existing parameter
        $docs['paths'][$path]['get']['parameters'] = array_values(array_filter($docs['paths'][$path]['get']['parameters'], function ($param) {
            return $param['name'] !== 'bar';
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