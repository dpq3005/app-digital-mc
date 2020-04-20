<?php

namespace App\Entity\BenefitProvider;

use App\Entity\AbstractThing;
use App\Entity\Dmc\MedicalChit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BenefitProvider\BenefitProductRepository")
 * @ORM\Table(name="benefit_provider__product")
 */
class BenefitProduct extends AbstractThing
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dmc\MedicalChit", mappedBy="product")
     */
    private $medicalChits;

    public function __construct()
    {
        parent::__construct();
        $this->medicalChits = new ArrayCollection();
    }

    /**
     * @return Collection|MedicalChit[]
     */
    public function getMedicalChits(): Collection
    {
        return $this->medicalChits;
    }

    public function addMedicalChit(MedicalChit $medicalChit): self
    {
        if (!$this->medicalChits->contains($medicalChit)) {
            $this->medicalChits[] = $medicalChit;
            $medicalChit->setProduct($this);
        }

        return $this;
    }

    public function removeMedicalChit(MedicalChit $medicalChit): self
    {
        if ($this->medicalChits->contains($medicalChit)) {
            $this->medicalChits->removeElement($medicalChit);
            // set the owning side to null (unless already changed)
            if ($medicalChit->getProduct() === $this) {
                $medicalChit->setProduct(null);
            }
        }

        return $this;
    }
}
