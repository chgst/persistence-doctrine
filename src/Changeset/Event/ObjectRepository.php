<?php

namespace Changeset\Event;

use Doctrine\Common\Persistence\ObjectManager;

class ObjectRepository implements RepositoryInterface
{
    /** @var ObjectManager */
    private $manager;

    /** @var string */
    private $eventClass;

    /**
     * ObjectRepository constructor.
     *
     * @param ObjectManager $manager
     * @param string $eventClass
     */
    public function __construct(ObjectManager $manager, string $eventClass)
    {
        $this->manager = $manager;
        $this->eventClass = $eventClass;
    }

    public function create(): EventInterface
    {
        return new $this->eventClass;
    }

    public function append(EventInterface $event)
    {
        $this->manager->persist($event);
        $this->manager->flush($event);
    }
}
