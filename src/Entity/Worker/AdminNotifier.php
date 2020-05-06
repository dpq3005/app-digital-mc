<?php

namespace App\Entity\Worker;

use App\Entity\AbstractThing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Worker\AdminNotifierRepository")
 * @ORM\Table(name="worker__admin_notifier")
 */
class AdminNotifier extends AbstractThing
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pid;

    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
    }

    public function getPid(): ?string
    {
        return $this->pid;
    }

    public function setPid(?string $pid): self
    {
        $this->pid = $pid;

        return $this;
    }
}
