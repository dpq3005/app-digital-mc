<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\SimpleDtoFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\BenefitProvider\FindBenefitProducts;

/**
 * Class BenefitProduct
 * @package App\Dto\BenefitProduct
 * @ApiResource(
 *     itemOperations={
 *     "get"={
 *     "requirements"={"id"=".+"},
 *         "read"=false,
 *     }},
 *     collectionOperations={
 *     "get",
 *     "find_benefit_products"
 *     }
 * )
 * @ApiFilter(SimpleDtoFilter::class, properties={"organisationUuid": "exact"})
 */
class BenefitProduct
{
    /**
     * @var string
     * @ApiProperty(identifier=true)
     */
    protected $uuid;

    /**
     * @var string|null
     * @ApiProperty()
     */
    protected $name;

    /**
     * @var string|null
     * @ApiProperty()
     */
    protected $productUuid;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getProductUuid(): ?string
    {
        return $this->productUuid;
    }

    /**
     * @param string|null $productUuid
     */
    public function setProductUuid(?string $productUuid): void
    {
        $this->productUuid = $productUuid;
    }
}