<?php

namespace Changeset\Event;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ORM\QueryBuilder;

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

    public function getIterator(): \Iterator
    {
        $repository = $this->manager->getRepository($this->eventClass);

        if (method_exists($repository, 'getIterator'))
        {
            return $repository->getIterator();
        }

        if (is_subclass_of($repository, 'Doctrine\ODM\MongoDB\DocumentRepository'))
        {
            return $this->getMongoDbIterator($repository);
        }

        if(is_subclass_of($repository, 'Doctrine\ORM\EntityRepository'))
        {
            return $this->getORMIterator($repository);
        }

        throw new \InvalidArgumentException('Unable to construct iterator');
    }

    private function getMongoDbIterator($repository)
    {
        /** @var Builder $qb */
        $qb = $repository->createQueryBuilder('e');

        return $qb
            ->sort('createdAt', 'asc')
            ->getQuery()
            ->getIterator()
        ;
    }

    private function getORMIterator($repository)
    {
        /** @var QueryBuilder $qb */
        $qb = $repository->createQueryBuilder('e');

        return $qb
            ->orderBy('e.createdAt ASC')
            ->getQuery()
            ->iterate()
        ;
    }
}
