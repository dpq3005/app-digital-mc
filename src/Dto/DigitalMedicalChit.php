<?php

namespace App\Dto\Dmc;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\SimpleDtoFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\BenefitProvider\FindOneBeneficiaryByNric;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class DigitalMedicalChit
 * @package App\Dto\DigitalMedicalChit
 * @ApiResource(
 *     messenger=true,
 *     itemOperations={"delete","get"={
 *     "requirements"={"id"=".+"},
 *     }},
 *     collectionOperations={ "post", "get"
 *     },
 *     denormalizationContext={"groups"={"write"}}
 * )
 * @ApiFilter(SimpleDtoFilter::class, properties={"organisationUuid": "exact"})
 */
class DigitalMedicalChit
{
    /**
     * @var string
     * @ApiProperty(identifier=true)
     */
    protected $uuid;

    /**
     * @var string|null
     * @ApiProperty()
     * @Groups("write")
     */
    protected $beneficiaryName;

    /**
     * @var string|null
     * @ApiProperty()
     * @Groups("write")
     */
    protected $beneficiaryNric;

    /**
     * @var string|null
     * @ApiProperty()
     * @Groups("write")
     */
    protected $product;

    /**
     * @var array|null
     * @ApiProperty()
     * @Groups("write")
     */
    protected $merchants;

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return string|null
     */
    public function getProduct(): ?string
    {
        return $this->product;
    }

    /**
     * @param string|null $product
     */
    public function setProduct(?string $product): void
    {
        $this->product = $product;
    }

    /**
     * @return array|null
     */
    public function getMerchants(): ?array
    {
        return $this->merchants;
    }

    /**
     * @param array|null $merchants
     */
    public function setMerchants(?array $merchants): void
    {
        $this->merchants = $merchants;
    }

    /**
     * @return string|null
     */
    public function getBeneficiaryName(): ?string
    {
        return $this->beneficiaryName;
    }

    /**
     * @param string|null $beneficiaryName
     */
    public function setBeneficiaryName(?string $beneficiaryName): self
    {
        $this->beneficiaryName = $beneficiaryName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBeneficiaryNric(): ?string
    {
        return $this->beneficiaryNric;
    }

    /**
     * @param string|null $beneficiaryNric
     */
    public function setBeneficiaryNric(?string $beneficiaryNric): self
    {
        $this->beneficiaryNric = $beneficiaryNric;
        return $this;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }
}