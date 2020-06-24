<?php

namespace App\Entity\EventSourcing;

use App\Entity\Dmc\MedicalChit;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="es__event__medical_chit")
 * @ORM\HasLifecycleCallbacks()
 */
class MedicalChitEvent
{
    const EVENT_DMC_CREATED = 'DMC_CREATED';
    const EVENT_DMC_DELETED = 'DMC_DELETED';
    const EVENT_DMC_REDEEMED = 'DMC_REDEEMED';

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->createdAtString = $this->createdAt->format('Y-m-d H:i:s.u');
    }

    public function setCreatedAt(\DateTime $dateTime)
    {
        $this->createdAt = $dateTime;
        $this->createdAtString = $this->createdAt->format('Y-m-d H:i:s.u');
    }

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer|null
     * @ORM\Column(type="integer",options={"unsigned":true}, nullable=true)
     */
    public $objectId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $objectUuid;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $version = 1;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $action;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $createdAtString;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    public $payload = [];

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dmc\MedicalChit")
     * @ORM\JoinColumn(name="id_medical_chit", onDelete="SET NULL")
     */
    public $medicalChit;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getCreatedAtString(): string
    {
        return $this->createdAtString;
    }

    public function getMedicalChit(): ?MedicalChit
    {
        return $this->medicalChit;
    }

    public function setMedicalChit(?MedicalChit $medicalChit): self
    {
        $this->medicalChit = $medicalChit;

        return $this;
    }
}