<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\SimpleDtoFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\BenefitProvider\FindOneBeneficiaryByNric;

/**
 * Class Beneficiary
 * @package App\Dto\Beneficiary
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     itemOperations={"get"={
 *     "requirements"={"id"=".+"},
 *     }},
 *     collectionOperations={
 *     "find_one_by_nric"={
 *         "method"="GET",
 *         "path"="/benefit-providers/{id}/find-one-beneficiary-by-nric/{nric}",
 *         "controller"=FindOneBeneficiaryByNric::class,
 *     "requirements"={"id"=".+", "nric"=".+"},
 *         "read"=false,
 *       }
 *     }
 * )
 * @ApiFilter(SimpleDtoFilter::class, properties={})
 */
class Beneficiary
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
    protected $nric;

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
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
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
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNric(): ?string
    {
        return $this->nric;
    }

    /**
     * @param string|null $nric
     */
    public function setNric(?string $nric): self
    {
        $this->nric = $nric;
        return $this;
    }
}