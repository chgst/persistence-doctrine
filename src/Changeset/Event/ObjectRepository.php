<?php

namespace Changeset\Event;

use Doctrine\Common\Persistence\ObjectManager;

class ObjectRepository implements RepositoryInterface
{
    /** @var ObjectManager */
    private $manager;

    /**
     * ObjectRepository constructor.
     *
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function append(EventInterface $event)
    {
        $this->manager->persist($event);
        $this->manager->flush($event);
    }
}
