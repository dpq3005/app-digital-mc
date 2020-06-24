<?php

namespace App\DoctrineListener;

use App\Entity\AbstractThing;
use App\Entity\Principal\Principal;
use App\Entity\WellnessSystem;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\ManagerRegistry;

class AbstractThingListener
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function preSave(AbstractThing $thing, LifecycleEventArgs $event)
    {
        $manager = $event->getObjectManager();
    }

    public function preFlush(AbstractThing $thing, PreFlushEventArgs $event)
    {

    }

    public function preUpdate(AbstractThing $thing, PreUpdateEventArgs $event)
    {
        $this->preSave($thing, $event);
    }

    public function prePersist(AbstractThing $thing, LifecycleEventArgs $event)
    {
        $this->preSave($thing, $event);
    }

}