<?php

namespace spec\Changeset\Aggregate;

use Changeset\Aggregate\ObjectRepository;
use Changeset\Event\EventInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectRepositorySpec extends ObjectBehavior
{
    function let(ObjectManager $manager)
    {
        $this->beConstructedWith($manager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ObjectRepository::class);
    }

    function it_can_append_event_to_data_store(ObjectManager $manager, EventInterface $event)
    {
        $manager->persist($event)->shouldBeCalled();

        $this->append($event);
    }
}
