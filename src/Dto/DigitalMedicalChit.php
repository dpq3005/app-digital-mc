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
 * @ApiFilter(SimpleDtoFilter::class, properties={"telemedEnabled": "exact","organisationUuid": "exact", "beneficiaryName": "exact", "beneficiaryNric": "exact","redeemed": "exact","expired": "exact"})
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
     */
    protected $productUuid;

    /**
     * @var string|null
     * @ApiProperty()
     */
    protected $benefitProviderName;

    /**
     * @var string|null
     * @ApiProperty()
     */
    protected $benefitProviderOrganisationUuid;

    /**
     * @var boolean|null
     * @ApiProperty()
     * @Groups("write")
     */
    protected $telemedEnabled = false;

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
     */
    protected $beneficiaryPhone;


    /**
     * @var string|null
     * @ApiProperty()
     */
    protected $medDeliveryAddress;

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


    public function isTelemedEnabled(): bool
    {
        return !empty($this->telemedEnabled);
    }

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
    public function setMerchants(?array $merchants): self
    {
        $this->merchants = $merchants; return $this;
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

    /**
     * @return bool|null
     */
    public function getTelemedEnabled(): ?bool
    {
        return $this->telemedEnabled;
    }

    /**
     * @param bool|null $telemedEnabled
     */
    public function setTelemedEnabled(?bool $telemedEnabled): void
    {
        $this->telemedEnabled = $telemedEnabled;
    }


    /**
     * @return string|null
     */
    public function getMedDeliveryAddress(): ?string
    {
        return $this->medDeliveryAddress;
    }

    /**
     * @param string|null $medDeliveryAddress
     */
    public function setMedDeliveryAddress(?string $medDeliveryAddress): void
    {
        $this->medDeliveryAddress = $medDeliveryAddress;
    }

    /**
     * @return string|null
     */
    public function getBeneficiaryPhone(): ?string
    {
        return $this->beneficiaryPhone;
    }

    /**
     * @param string|null $beneficiaryPhone
     */
    public function setBeneficiaryPhone(?string $beneficiaryPhone): void
    {
        $this->beneficiaryPhone = $beneficiaryPhone;
    }

    /**
     * @return string|null
     */
    public function getBenefitProviderName(): ?string
    {
        return $this->benefitProviderName;
    }

    /**
     * @param string|null $benefitProviderName
     */
    public function setBenefitProviderName(?string $benefitProviderName): void
    {
        $this->benefitProviderName = $benefitProviderName;
    }

    /**
     * @return string|null
     */
    public function getBenefitProviderOrganisationUuid(): ?string
    {
        return $this->benefitProviderOrganisationUuid;
    }

    /**
     * @param string|null $benefitProviderOrganisationUuid
     */
    public function setBenefitProviderOrganisationUuid(?string $benefitProviderOrganisationUuid): void
    {
        $this->benefitProviderOrganisationUuid = $benefitProviderOrganisationUuid;
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
    public function setProductUuid(?string $productUuid): self
    {
        $this->productUuid = $productUuid;
        return $this;
    }
}