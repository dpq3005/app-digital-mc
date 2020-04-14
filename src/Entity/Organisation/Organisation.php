<?php

namespace App\Entity\Organisation;

use App\Entity\AbstractThing;
use App\Entity\BenefitProvider\BenefitProvider;
use App\Entity\Security\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Organisation\OrganisationRepository")
 */
class Organisation extends  AbstractThing
{

    public function findOneUserByUsername($username)
    {
        $c = Criteria::create();
        $e = Criteria::expr();
        $c->setMaxResults(1);
        $c->where($e->eq('username', $username));

        return $this->users->matching($c)->first();
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $supervisorCode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Security\User", mappedBy="organisation")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\BenefitProvider\BenefitProvider", mappedBy="organisation", cascade={"persist", "remove"})
     */
    private $benefitProvider;

    public function __construct()
    {
        parent::__construct();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupervisorCode(): ?string
    {
        return $this->supervisorCode;
    }

    public function setSupervisorCode(?string $supervisorCode): self
    {
        $this->supervisorCode = $supervisorCode;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setOrganisation($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getOrganisation() === $this) {
                $user->setOrganisation(null);
            }
        }

        return $this;
    }

    public function getBenefitProvider(): ?BenefitProvider
    {
        return $this->benefitProvider;
    }

    public function setBenefitProvider(?BenefitProvider $benefitProvider): self
    {
        $this->benefitProvider = $benefitProvider;

        // set (or unset) the owning side of the relation if necessary
        $newOrganisation = null === $benefitProvider ? null : $this;
        if ($benefitProvider->getOrganisation() !== $newOrganisation) {
            $benefitProvider->setOrganisation($newOrganisation);
        }

        return $this;
    }
}
