<?php

namespace App\Entity\EventSourcing;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventSourcing\EventLockRepository")
 * @ORM\Table(name="es__event__lock__invoicing")
 */
class EventLock
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $aggregateUuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="json")
     */
    private $owner = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $eventName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resourceName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resourceUuid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAggregateUuid(): ?string
    {
        return $this->aggregateUuid;
    }

    public function setAggregateUuid(?string $aggregateUuid): self
    {
        $this->aggregateUuid = $aggregateUuid;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): ?array
    {
        return $this->owner;
    }

    public function setOwner(array $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): self
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getResourceName(): ?string
    {
        return $this->resourceName;
    }

    public function setResourceName(string $resourceName): self
    {
        $this->resourceName = $resourceName;

        return $this;
    }

    public function getResourceUuid(): ?string
    {
        return $this->resourceUuid;
    }

    public function setResourceUuid(string $resourceUuid): self
    {
        $this->resourceUuid = $resourceUuid;

        return $this;
    }
}
