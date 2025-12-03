<?php

namespace Chgst\Event;

use Doctrine\Persistence\ObjectManager;

class ObjectRepository implements RepositoryInterface
{

    private ObjectManager $manager;

    private string $eventClass;

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
        $this->manager->flush();
    }

    public function getIterator(): \Iterator
    {
        $repository = $this->manager->getRepository($this->eventClass);

        if ($repository)
        {
            if ($repository instanceof \IteratorAggregate)
            {
                return $repository->getIterator();
            }

            if (method_exists($repository, 'createQueryBuilder'))
            {
                $queryBuilder = $repository->createQueryBuilder('e');

                if (method_exists($queryBuilder, 'sort'))
                {
                    return $this->getMongoDbIterator($queryBuilder);
                }

                if (method_exists($queryBuilder, 'orderBy'))
                {
                    return $this->getORMIterator($queryBuilder);
                }
            }
        }

        throw new \InvalidArgumentException('Unable to construct iterator');
    }

    private function getMongoDbIterator($queryBuilder)
    {
        $query = $queryBuilder
            ->sort('createdAt', 'asc')
            ->getQuery()
        ;

        if (method_exists($query, 'toIterator'))
        {
            $iterator = $query->toIterator();

            return $iterator instanceof \Iterator ? $iterator : new \IteratorIterator(new \ArrayIterator($iterator));
        }

        return $query->getIterator();
    }

    private function getORMIterator($queryBuilder)
    {
        $query = $queryBuilder
            ->orderBy('e.createdAt','ASC')
            ->getQuery()
        ;

        if (method_exists($query, 'toIterable'))
        {
            $iterator = $query->toIterable();

            return $iterator instanceof \Iterator ? $iterator : new \IteratorIterator(new \ArrayIterator(is_array($iterator) ? $iterator : iterator_to_array($iterator)));
        }

        return $query->iterate();
    }
}
