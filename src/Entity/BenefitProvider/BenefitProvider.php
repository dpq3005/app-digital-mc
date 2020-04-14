<?php

namespace App\Entity\BenefitProvider;

use App\Entity\AbstractThing;
use App\Entity\Organisation\Organisation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BenefitProvider\BenefitProviderRepository")
 * @ORM\Table(name="benefit_provider")
 */
class BenefitProvider extends AbstractThing
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Organisation\Organisation", inversedBy="benefitProvider", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $organisation;

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }
}
