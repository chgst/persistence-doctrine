<?php

namespace spec\Changeset\Event;

use Changeset\Common\HasPayloadTrait;
use Changeset\Common\OnAggregateTrait;
use Changeset\Event\EventInterface;
use Changeset\Event\EventTrait;
use Changeset\Event\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;

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
        $manager->flush($event)->shouldBeCalled();

        $this->append($event);
    }
}


class TestEvent implements EventInterface
{
    use EventTrait, HasPayloadTrait, OnAggregateTrait;
}