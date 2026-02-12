<?php

namespace spec\Chgst\Event;

use Chgst\Common\HasPayloadTrait;
use Chgst\Common\OnAggregateTrait;
use Chgst\Event\EventInterface;
use Chgst\Event\EventTrait;
use Chgst\Event\ObjectRepository;
use DG\BypassFinals;
use Doctrine\ODM\MongoDB\Iterator\Iterator;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Query\Query;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query as ORMQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectRepositorySpec extends ObjectBehavior
{
    function let(ObjectManager $manager)
    {
        BypassFinals::enable();
        $this->beConstructedWith($manager, TestEvent::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ObjectRepository::class);
    }

    function it_can_create_new_event_instance_baseed_on_class_name_passed_as_param_to_contructor()
    {
        $this->create()->shouldReturnAnInstanceOf(TestEvent::class);
    }

    function it_can_append_event_to_data_store(ObjectManager $manager, EventInterface $event)
    {
        $manager->persist($event)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->append($event);
    }

    function it_throws_exception_if_it_cannot_construct_iterator(
        ObjectManager $manager,
        \Doctrine\Persistence\ObjectRepository $repository
    )
    {
        $manager->getRepository(Argument::any())->willReturn($repository);

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('getIterator')
        ;
    }

    function it_can_get_iterator_for_the_event_stream_if_it_is_defined(
        ObjectManager $manager,
        IterableRepository $repo1,
        \Iterator $iterator
    )
    {
        $manager->getRepository(Argument::any())->willReturn($repo1);

        $repo1->getIterator()->willReturn($iterator);

        $this->getIterator();

    }

    function it_can_get_iterator_for_the_event_stream_ORM(
        ObjectManager $manager,
        EntityRepository $repository,
        QueryBuilder $queryBuilder,
        ORMQuery $query,
        \Iterator $iterator
    )
    {
        $manager->getRepository(Argument::any())->willReturn($repository);

        $repository->createQueryBuilder(Argument::any())->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);
        $queryBuilder->orderBy(Argument::any(), Argument::any())->shouldBeCalled()->willReturn($queryBuilder);

        $query->toIterable()->willReturn($iterator);

        $this->getIterator();
    }

    function it_can_get_iterator_for_the_event_stream_MongoDB(
        ObjectManager $manager,
        DocumentRepository $repository,
        Builder $queryBuilder,
        Query $query,
        Iterator $iterator
    )
    {
        $manager->getRepository(Argument::any())->willReturn($repository);

        $repository->createQueryBuilder(Argument::any())->willReturn($queryBuilder);

        $queryBuilder->sort(Argument::any(), Argument::any())->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);

        $query->getIterator()->willReturn($iterator);

        $this->getIterator();
    }


}


class TestEvent implements EventInterface
{
    use EventTrait, HasPayloadTrait, OnAggregateTrait;
}

interface IterableRepository extends \Doctrine\Persistence\ObjectRepository, \IteratorAggregate {}
