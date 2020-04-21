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
 *     attributes={"security"="is_granted('ROLE_USER')"},
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
     */
    protected $productName;

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
     */
    protected $code;

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
     * @var \DateTime|null
     * @ApiProperty()
     */
    protected $createdAt;

    /**
     * @var bool|null
     * @ApiProperty()
     */
    protected $expired;

    /**
     * @var bool|null
     * @ApiProperty()
     */
    protected $redeemed;

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

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool|null
     */
    public function getRedeemed(): ?bool
    {
        return $this->redeemed;
    }

    /**
     * @param bool|null $redeemed
     */
    public function setRedeemed(?bool $redeemed): void
    {
        $this->redeemed = $redeemed;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return bool|null
     */
    public function getExpired(): ?bool
    {
        return $this->expired;
    }

    /**
     * @param bool|null $expired
     */
    public function setExpired(?bool $expired): void
    {
        $this->expired = $expired;
    }

    /**
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * @param string|null $productName
     */
    public function setProductName(?string $productName): void
    {
        $this->productName = $productName;
    }
}