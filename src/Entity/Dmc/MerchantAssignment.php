<?php

namespace App\Entity\Dmc;

use App\Entity\AbstractThing;
use App\Entity\Merchant\Merchant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dmc\MerchantAssignmentRepository")
 * @ORM\Table(name="dmc__merchant_assignment",uniqueConstraints={@ORM\UniqueConstraint(name="merchant_assignment_unique", columns={"id_merchant", "id_medical_chit"})})
 */
class MerchantAssignment extends AbstractThing
{

    public function initUuid($prefix = null)
    {
        $prefix = 'MA';
        parent::initUuid($prefix);
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dmc\MedicalChit", inversedBy="merchantAssignments")
     * @ORM\JoinColumn(name="id_medical_chit", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $medicalChit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Merchant\Merchant", inversedBy="merchantAssignments")
     * @ORM\JoinColumn(name="id_merchant", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $merchant;

    public function getMedicalChit(): ?MedicalChit
    {
        return $this->medicalChit;
    }

    public function setMedicalChit(?MedicalChit $medicalChit): self
    {
        $this->medicalChit = $medicalChit;

        return $this;
    }

    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(?Merchant $merchant): self
    {
        $this->merchant = $merchant;

        return $this;
    }
}
