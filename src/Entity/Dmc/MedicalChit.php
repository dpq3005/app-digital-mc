<?php

namespace App\Entity\Dmc;

use App\Entity\AbstractThing;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Dmc\MedicalChitRepository")
 */
class MedicalChit extends AbstractThing
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dmc\MerchantAssignment", mappedBy="medicalChit")
     */
    private $merchantAssignments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nric;

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

    public function getNric(): ?string
    {
        return $this->nric;
    }

    public function setNric(?string $nric): self
    {
        $this->nric = $nric;

        return $this;
    }
}
