<?php

namespace App\Entity\Merchant;

use App\Entity\AbstractThing;
use App\Entity\Dmc\MerchantAssignment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Merchant\MerchantRepository")
 */
class Merchant extends AbstractThing
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dmc\MerchantAssignment", mappedBy="merchant")
     */
    private $merchantAssignments;

    public function __construct()
    {
        parent::__construct();
        $this->merchantAssignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMerchantAssignmentByDmcUuid($dmcUuid)
    {
        /** @var MerchantAssignment $assignment */
        foreach ($this->merchantAssignments as $assignment) {
            if ($assignment->getMedicalChit()->getUuid() === $dmcUuid) {
                return $assignment;
            }
        }
        return null;
    }

    public function getMerchantAssignmentsByProductUuid($productUuid)
    {
        $c = Criteria::create();
        $e = Criteria::expr();
        $c->andWhere($e->eq('medicalChit.productUuid', $productUuid));
        return $this->merchantAssignments->matching($c);
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
            $merchantAssignment->setMerchant($this);
        }

        return $this;
    }

    public function removeMerchantAssignment(MerchantAssignment $merchantAssignment): self
    {
        if ($this->merchantAssignments->contains($merchantAssignment)) {
            $this->merchantAssignments->removeElement($merchantAssignment);
            // set the owning side to null (unless already changed)
            if ($merchantAssignment->getMerchant() === $this) {
                $merchantAssignment->setMerchant(null);
            }
        }

        return $this;
    }
}
