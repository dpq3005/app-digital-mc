<?php

namespace App\Entity\BenefitProvider;

use App\Entity\AbstractThing;
use App\Entity\Dmc\MedicalChit;
use App\Entity\Organisation\Organisation;
use App\Service\ThingService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BenefitProvider\BenefitProviderRepository")
 * @ORM\Table(name="benefit_provider")
 */
class BenefitProvider extends AbstractThing
{
    public function initUuid($prefix = null)
    {
        return;
    }

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Organisation\Organisation", inversedBy="benefitProvider", cascade={"persist"})
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $organisation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dmc\MedicalChit", mappedBy="benefitProvider")
     */
    private $medicalChits;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $telemedEnabled;

    public function __construct()
    {
        parent::__construct();
        $this->medicalChits = new ArrayCollection();
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
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
            $medicalChit->setBenefitProvider($this);
        }

        return $this;
    }

    public function removeMedicalChit(MedicalChit $medicalChit): self
    {
        if ($this->medicalChits->contains($medicalChit)) {
            $this->medicalChits->removeElement($medicalChit);
            // set the owning side to null (unless already changed)
            if ($medicalChit->getBenefitProvider() === $this) {
                $medicalChit->setBenefitProvider(null);
            }
        }

        return $this;
    }

    public function getTelemedEnabled(): ?bool
    {
        return $this->telemedEnabled;
    }

    public function setTelemedEnabled(bool $telemedEnabled): self
    {
        $this->telemedEnabled = $telemedEnabled;

        return $this;
    }


}
