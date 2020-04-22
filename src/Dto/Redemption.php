<?php

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Class Redemption
 * @package App\Dto
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     itemOperations={"get"={
 *     "requirements"={"id"=".+"},
 *     }},
 *     collectionOperations={"get"
 *     },
 *     denormalizationContext={"groups"={"write"}}
 * )
 */
class Redemption
{
    /**
     * @var string
     * @ApiProperty(identifier=true)
     */
    protected $uuid;

    /**
     * @var string|null
     * @ApiProperty()
     */
    protected $medicalChitUuid;

    /**
     * @var string|null
     * @ApiProperty()
     */
    protected $name;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getMedicalChitUuid(): ?string
    {
        return $this->medicalChitUuid;
    }

    /**
     * @param string|null $medicalChitUuid
     */
    public function setMedicalChitUuid(?string $medicalChitUuid): void
    {
        $this->medicalChitUuid = $medicalChitUuid;
    }
}