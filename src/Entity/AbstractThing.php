<?php

namespace App\Entity;

use App\Service\ThingService;
use Bean\Thing\Model\ThingInterface;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractThing extends \Bean\Thing\Model\Thing
{
    /**
     * DATA_{PROPERTY} to access the property using const
     * Eg: $this->{ThingModel::DATA_ADDRESS}
     */
    const DATA_ADDRESS = 'address';

    public function getPayload()
    {
        $this->initUuid();

        return [
            'name' => $this->name,
            'enabled' => $this->enabled,
            'createdAt' => $this->createdAt,
            'description' => $this->description,
            'deleted' => $this->deleted,
            'code' => $this->code,
            'uuid' => $this->uuid,
            'id' => $this->id,
            'slug' => $this->slug,
            'state' => $this->state,
            'legacyId' => $this->legacyId,
            'updatedAt' => $this->updatedAt,
            'data' => $this->data
        ];
    }

    public function __get($field)
    {
        if (array_key_exists($field, $this->data)) {
            return $this->data[$field];
        } else {
            return null;
        }
    }

    public function preSave()
    {
        $this->initUuid();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function resetData()
    {
        $this->slug = null;
        $this->uuid = null;
        $this->createdAt = new \DateTime();
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function initData()
    {
        if (empty($this->slug)) {
            $this->initSlug();
        }
        if (empty($this->uuid)) {
            $this->initUuid();
        }
        return $this;
    }

    public function initUuid()
    {
        if (empty($this->uuid)) {
            $reflect = new \ReflectionClass($this);
            $this->uuid = ThingService::generateUuid($reflect->getShortName());
        }
    }

    public function initSlug($slug = null)
    {
        if (empty($slug)) {
            if (empty($this->name)) {
                return;
            }
            $slug = $this->name;
        }
        $this->slug = Slugify::create()->slugify($slug);
    }

    public function setCode(?string $code): self
    {
        if (!empty($code)) {
            $code = strtoupper($code);
        }
        $this->code = $code;

        return $this;
    }

    /**
     * @ORM\Column(type="string", length=128, nullable=true, unique=true)
     */
    protected $code;

    /**
     * @var array|null
     * @ORM\Column(type="json", nullable=true)
     */
    protected $data = [];

    /**
     * @var array|null
     * @ORM\Column(type="json", nullable=true)
     */
    protected $eventLog = [];

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): ThingInterface
    {
        $this->data = $data;

        return $this;
    }

    public function cast($destination)
    {
        return ThingService::cast($this, $destination);
    }

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint", options={"unsigned":true})
     */
    protected $id;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $legacyId;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $slug;

    /**
     * A thing may have a state such as DRAFT, PUBLISHED, ARCHIVED
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $state = self::STATE_DRAFT;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean")
     */
    protected $enabled = false;

    /**
     * @var boolean|null
     * @ORM\Column(type="boolean")
     */
    protected $deleted = false;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    protected $uuid;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getLegacyId(): ?int
    {
        return $this->legacyId;
    }

    public function setLegacyId(?int $legacyId): self
    {
        $this->legacyId = $legacyId;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

}