<?php

namespace App\Entity\Dmc;

use App\Entity\AbstractThing;
use App\Entity\BenefitProvider\BenefitProduct;
use App\Entity\BenefitProvider\BenefitProvider;
use App\Entity\EventSourcing\MedicalChitEvent;
use App\Service\ThingService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dmc\MedicalChitRepository")
 * @ORM\Table(name="dmc__medical_chit")
 * @ORM\HasLifecycleCallbacks()
 */
class MedicalChit extends AbstractThing
{
    const STATE_NEW = 'NEW';

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function preSave()
    {
        parent::preSave();
        $this->initCode();
    }

    public function initCode()
    {
        if (empty($this->code)) {
            $now = new \DateTime();
            $this->code = 'DMC';
            $this->code .= '-'.$now->format('ym');
            $this->code .= '-'.$now->format('dH');
            $this->code .= '-'.ThingService::generate4DigitCode();
            $this->code .= '-'.ThingService::generate4DigitCode();
        }
    }

    public function isExpired(): bool
    {
        if ($this->expired) {
            return true;
        }

        $now = new \DateTime();
        $this->expired = $this->expireAt < $now;
        return $this->expired;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dmc\MerchantAssignment", mappedBy="medicalChit")
     */
    private $merchantAssignments;

    /**
     * @var BenefitProduct|null
     * @ORM\ManyToOne(targetEntity="App\Entity\BenefitProvider\BenefitProduct", inversedBy="medicalChits")
     * @ORM\JoinColumn(name="id_benefit_product")
     */
    private $benefitProduct;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productUuid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productName;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $merchantUuids = [];

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $redeemed = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $beneficiaryName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $beneficiaryNric;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $expired = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $expireIn;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expireAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BenefitProvider\BenefitProvider", inversedBy="medicalChits")
     * @ORM\JoinColumn(name="id_benefit_provider", onDelete="SET NULL")
     */
    private $benefitProvider;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $benefitProviderUuid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $benefitProductUuid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $redemptionUuid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $redeemedAtMerchantUuid;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $redeemedAt;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $merchantAssignmentsInit = false;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $adminNotified = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $telemedEnabled;

    public function __construct()
    {
        parent::__construct();
        $this->merchantAssignments = new ArrayCollection();
    }

    public function isTelemedEnabled(): bool
    {
        return !empty($this->telemedEnabled);
    }

    /**
     * @return Collection|MerchantAssignment[]
     */
    public function getMerchantAssignments(): Collection
    {
        return $this->merchantAssignments;
    }

    public function addMerchantAssignment(MerchantAssignment $merchantAssignment): self
    {
        if (!$this->merchantAssignments->contains($merchantAssignment)) {
            $this->merchantAssignments[] = $merchantAssignment;
            $merchantAssignment->setMedicalChit($this);
        }

        return $this;
    }

    public function removeMerchantAssignment(MerchantAssignment $merchantAssignment): self
    {
        if ($this->merchantAssignments->contains($merchantAssignment)) {
            $this->merchantAssignments->removeElement($merchantAssignment);
            // set the owning side to null (unless already changed)
            if ($merchantAssignment->getMedicalChit() === $this) {
                $merchantAssignment->setMedicalChit(null);
            }
        }

        return $this;
    }

    public function getBenefitProduct(): ?BenefitProduct
    {
        return $this->benefitProduct;
    }

    public function setBenefitProduct(?BenefitProduct $benefitProduct): self
    {
        $this->benefitProduct = $benefitProduct;

        return $this;
    }

    public function getProductUuid(): ?string
    {
        return $this->productUuid;
    }

    public function setProductUuid(?string $productUuid): self
    {
        $this->productUuid = $productUuid;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getMerchantUuids(): ?array
    {
        return $this->merchantUuids;
    }

    public function setMerchantUuids(?array $merchantUuids): self
    {
        $this->merchantUuids = $merchantUuids;

        return $this;
    }

    public function getRedeemed(): ?bool
    {
        return $this->redeemed;
    }

    public function setRedeemed(bool $redeemed): self
    {
        $this->redeemed = $redeemed;

        return $this;
    }

    public function getBeneficiaryName(): ?string
    {
        return $this->beneficiaryName;
    }

    public function setBeneficiaryName(?string $beneficiaryName): self
    {
        $this->beneficiaryName = $beneficiaryName;

        return $this;
    }

    public function getBeneficiaryNric(): ?string
    {
        return $this->beneficiaryNric;
    }

    public function setBeneficiaryNric(?string $beneficiaryNric): self
    {
        $this->beneficiaryNric = $beneficiaryNric;

        return $this;
    }

    public function getExpired(): ?bool
    {
        return $this->expired;
    }

    public function setExpired(bool $expired): self
    {
        $this->expired = $expired;

        return $this;
    }

    public function getExpireIn(): ?int
    {
        return $this->expireIn;
    }

    public function setExpireIn(?int $expireIn): self
    {
        $this->expireIn = $expireIn;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expireAt;
    }

    public function setExpireAt(?\DateTimeInterface $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function getBenefitProvider(): ?BenefitProvider
    {
        return $this->benefitProvider;
    }

    public function setBenefitProvider(?BenefitProvider $benefitProvider): self
    {
        $this->benefitProvider = $benefitProvider;

        return $this;
    }

    public function getBenefitProviderUuid(): ?string
    {
        return $this->benefitProviderUuid;
    }

    public function setBenefitProviderUuid(?string $benefitProviderUuid): self
    {
        $this->benefitProviderUuid = $benefitProviderUuid;

        return $this;
    }

    public function getBenefitProductUuid(): ?string
    {
        return $this->benefitProductUuid;
    }

    public function setBenefitProductUuid(?string $benefitProductUuid): self
    {
        $this->benefitProductUuid = $benefitProductUuid;

        return $this;
    }

    public function getRedemptionUuid(): ?string
    {
        return $this->redemptionUuid;
    }

    public function setRedemptionUuid(?string $redemptionUuid): self
    {
        $this->redemptionUuid = $redemptionUuid;

        return $this;
    }

    public function getRedeemedAtMerchantUuid(): ?string
    {
        return $this->redeemedAtMerchantUuid;
    }

    public function setRedeemedAtMerchantUuid(?string $redeemedAtMerchantUuid): self
    {
        $this->redeemedAtMerchantUuid = $redeemedAtMerchantUuid;

        return $this;
    }

    public function getRedeemedAt(): ?\DateTimeInterface
    {
        return $this->redeemedAt;
    }

    public function setRedeemedAt(?\DateTimeInterface $redeemedAt): self
    {
        $this->redeemedAt = $redeemedAt;

        return $this;
    }

    public function getMerchantAssignmentsInit(): ?bool
    {
        return $this->merchantAssignmentsInit;
    }

    public function setMerchantAssignmentsInit(bool $merchantAssignmentsInit): self
    {
        $this->merchantAssignmentsInit = $merchantAssignmentsInit;

        return $this;
    }

    public function getAdminNotified(): ?bool
    {
        return $this->adminNotified;
    }

    public function setAdminNotified(?bool $adminNotified): self
    {
        $this->adminNotified = $adminNotified;

        return $this;
    }

    public function getTelemedEnabled(): ?bool
    {
        return $this->telemedEnabled;
    }

    public function setTelemedEnabled(?bool $telemedEnabled): self
    {
        $this->telemedEnabled = $telemedEnabled;

        return $this;
    }
}
