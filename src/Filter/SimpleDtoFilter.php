<?php

namespace App\Filter;

use ApiPlatform\Core\Api\FilterInterface;

class SimpleDtoFilter implements FilterInterface
{
    private $parameterName, $properties;

    public function __construct(string $parameterName = 'properties', array $properties = [])
    {
        $this->parameterName = $parameterName;
        $this->properties = array_merge(['pageSize' => 100], $properties);
    }

    /**
     * Gets the description of this filter for the given resource.
     *
     * Returns an array with the filter parameter names as keys and array with the following data as values:
     *   - property: the property where the filter is applied
     *   - type: the type of the filter
     *   - required: if this filter is required
     *   - strategy: the used strategy
     *   - is_collection (optional): is this filter is collection
     *   - swagger (optional): additional parameters for the path operation,
     *     e.g. 'swagger' => [
     *       'description' => 'My Description',
     *       'name' => 'My Name',
     *       'type' => 'integer',
     *     ]
     *   - openapi (optional): additional parameters for the path operation in the version 3 spec,
     *     e.g. 'openapi' => [
     *       'description' => 'My Description',
     *       'name' => 'My Name',
     *       'schema' => [
     *          'type' => 'integer',
     *       ]
     *     ]
     * The description can contain additional data specific to a filter.
     *
     * @see \ApiPlatform\Core\Swagger\Serializer\DocumentationNormalizer::getFiltersParameters
     */
    public function getDescription(string $resourceClass): array
    {
        $description = [];

        $properties = [];
        if (null === $properties) {

        }

        foreach ($properties as $property => $strategy) {

        }

        $filterParameterName = 'filterParameterName';
        $propertyName = 'propertyName';
        $typeOfField = 'typeOfField';
        $strategy = 'strategy';

        $description[$filterParameterName] = [
            'property' => $propertyName,
            'type' => $typeOfField,
            'required' => false,
            'strategy' => $strategy,
            'is_collection' => '[]' === substr((string) $filterParameterName, -2),
        ];

        $example = '';// sprintf('',
        //$this->parameterName
        //);
//        return $description;
        $properties = [];
        foreach ($this->properties as $property => $config) {
            $properties[$property] = [
                'property' => $property,
                'type' => 'string',
                'is_collection' => true,
                'required' => false,
                'swagger' => [
                    'description' => 'Example: '.$example,
                    'name' => "$property",
                    'type' => 'string',
                ],
                'openapi' => [
                    'description' => 'Example: '.$example,
                    'name' => "$property",
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
            ];
        }

        return $properties;

//        return array_merge($properties, [
//            "$this->parameterName[]" => [
//                'property' => null,
//                'type' => 'string',
//                'is_collection' => true,
//                'required' => false,
//                'swagger' => [
//                    'description' => 'Allows you to reduce the response to contain only the properties you need. If your desired property is nested, you can address it using nested arrays. Example: '.$example,
//                    'name' => "$this->parameterName[]",
//                    'type' => 'array',
//                    'items' => [
//                        'type' => 'string',
//                    ],
//                ],
//                'openapi' => [
//                    'description' => 'Allows you to reduce the response to contain only the properties you need. If your desired property is nested, you can address it using nested arrays. Example: '.$example,
//                    'name' => "$this->parameterName[]",
//                    'schema' => [
//                        'type' => 'array',
//                        'items' => [
//                            'type' => 'string',
//                        ],
//                    ],
//                ],
//            ],
//        ]);
    }
}