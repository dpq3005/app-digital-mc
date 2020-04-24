<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Controller\Redemption\RedeemMedicalChit;
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
 *     itemOperations={"delete",
 *     "redeem_dmc"={
 *         "method"="POST",
 *         "path"="/digital-medical-chits/{id}/redeem",
 *         "controller"=RedeemMedicalChit::class,
 *         "requirements"={"id"=".+"},
 *     },
 *     "get"={
 *     "requirements"={"id"=".+"},
 *     }},
 *     collectionOperations={ "post", "get"
 *     },
 *     denormalizationContext={"groups"={"write"}}
 * )
 * @ApiFilter(SimpleDtoFilter::class, properties={"organisationUuid": "exact", "beneficiaryName": "exact", "beneficiaryNric": "exact","redeemed": "exact","expired": "exact"})
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
     * @var string|null
     * @ApiProperty()
     * @Groups("write")
     */
    protected $benefitProduct;

    /**
     * @var array|null
     * @ApiProperty()
     * @Groups("write")
     */
    protected $merchants;

    /**
     * @var string|null
     * @ApiProperty()
     * @Groups("write")
     */
    protected $redeemedAtMerchantUuid;

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
    public function setProduct(?string $product): self
    {
        $this->product = $product;
        return $this;
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
    public function setRedeemed(?bool $redeemed): self
    {
        $this->redeemed = $redeemed;
        return $this;
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
    public function setCode(?string $code): self
    {
        $this->code = $code;
        return $this;
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
    public function setExpired(?bool $expired): self
    {
        $this->expired = $expired;
        return $this;
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
    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBenefitProduct(): ?string
    {
        return $this->benefitProduct;
    }

    /**
     * @param string|null $benefitProduct
     */
    public function setBenefitProduct(?string $benefitProduct): self
    {
        $this->benefitProduct = $benefitProduct;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedeemedAtMerchantUuid(): ?string
    {
        return $this->redeemedAtMerchantUuid;
    }

    /**
     * @param string|null $redeemedAtMerchantUuid
     */
    public function setRedeemedAtMerchantUuid(?string $redeemedAtMerchantUuid): void
    {
        $this->redeemedAtMerchantUuid = $redeemedAtMerchantUuid;
    }
}