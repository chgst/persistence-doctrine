<?php

namespace spec\Changeset\Event;

use Changeset\Common\HasPayloadTrait;
use Changeset\Common\OnAggregateTrait;
use Changeset\Event\EventInterface;
use Changeset\Event\EventTrait;
use Changeset\Event\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Query\Query;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectRepositorySpec extends ObjectBehavior
{
    function let(ObjectManager $manager)
    {
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

    function it_throws_exception_if_it_cannot_construct_iterator()
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('getIterator')
        ;
    }

    function it_can_get_iterator_for_the_event_stream_if_it_is_defined(
        ObjectManager $manager,
        \IteratorAggregate $repo1,
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
        AbstractQuery $query,
        \Iterator $iterator
    )
    {
        $manager->getRepository(Argument::any())->willReturn($repository);

        $repository->createQueryBuilder(Argument::any())->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);
        $queryBuilder->orderBy(Argument::any(), Argument::any())->shouldBeCalled()->willReturn($queryBuilder);

        $query->iterate()->willReturn($iterator);

        $this->getIterator();
    }

    function it_can_get_iterator_for_the_event_stream_MongoDB(
        ObjectManager $manager,
        DocumentRepository $repository,
        Builder $queryBuilder,
        Query $query,
        \Iterator $iterator
    )
    {
        $manager->getRepository(Argument::any())->willReturn($repository);

        $repository->createQueryBuilder(Argument::any())->willReturn($queryBuilder);

        $queryBuilder->getQuery()->willReturn($query);
        $queryBuilder->sort(Argument::any(), Argument::any())->shouldBeCalled()->willReturn($queryBuilder);

        $query->getIterator()->willReturn($iterator);

        $this->getIterator();
    }
}


class TestEvent implements EventInterface
{
    use EventTrait, HasPayloadTrait, OnAggregateTrait;
}
