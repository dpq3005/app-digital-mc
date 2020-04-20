<?php

namespace App\Entity\Dmc;

use App\Entity\AbstractThing;
use App\Entity\BenefitProvider\BenefitProduct;
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
        if (empty($this->expireAt)) {
            $this->expireAt = clone $this->createdAt;
            $this->expireAt->modify('+ '.$this->expireIn.' hours');
        }
    }

    public function initCode()
    {
        if (empty($this->code)) {
            $now = new \DateTime();
            $this->code = 'DMC';
            $this->code .= $now->format('ym');
            $this->code .= $now->format('dH');
            $this->code .= ThingService::generate4DigitCode();
            $this->code .= ThingService::generate4DigitCode();
        }
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
    private $expireIn = 36;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expireAt;

    public function __construct()
    {
        parent::__construct();
        $this->merchantAssignments = new ArrayCollection();
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
}
