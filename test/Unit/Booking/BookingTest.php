<?php

namespace Test\Unit\Booking;

use CartBooking\Booking\Booking;
use CartBooking\Publisher\Publisher;
use Test\AutoMockingTest;

class BookingTest extends AutoMockingTest
{
    /** @var  Booking */
    private $booking;

    public function setUp()
    {
        parent::setUp();
        $this->booking = new Booking(1, 1, \DateTimeImmutable::createFromFormat('Y-m-d', '2017-01-01'));
    }

    public function testSetOverseerOnly()
    {
        $overseer = $this->prophesize(Publisher::class);
        $overseer->isMale()->willReturn(true);
        $overseerId = 1;
        $overseer->getId()->willReturn($overseerId);
        $this->booking->setPublishers([$overseer->reveal()]);
        static::assertSame($overseerId, $this->booking->getOverseerId());
        static::assertSame(0, $this->booking->getPioneerId());
        static::assertSame(0, $this->booking->getPioneerBId());
    }

    public function testSetPioneerOnly()
    {
        $pioneer = $this->prophesize(Publisher::class);
        $pioneer->isMale()->willReturn(false);
        $pioneerId = 1;
        $pioneer->getId()->willReturn($pioneerId);
        $this->booking->setPublishers([$pioneer->reveal()]);
        static::assertSame(0, $this->booking->getOverseerId());
        static::assertSame($pioneerId, $this->booking->getPioneerId());
        static::assertSame(0, $this->booking->getPioneerBId());
    }

    public function testSetOverseerAndPublisher()
    {
        $overseer = $this->prophesize(Publisher::class);
        $pioneer = $this->prophesize(Publisher::class);
        $overseerId = 1;
        $pioneerId = 2;
        $overseer->isMale()->willReturn(true);
        $overseer->getId()->willReturn($overseerId);
        $pioneer->isMale()->willReturn(false);
        $pioneer->getId()->willReturn($pioneerId);
        $this->booking->setPublishers([$overseer->reveal(), $pioneer->reveal()]);
        static::assertSame($overseerId, $this->booking->getOverseerId());
        static::assertSame($pioneerId, $this->booking->getPioneerId());
        static::assertFalse($this->booking->isConfirmed());
    }

    public function testSetOverseerAnd2Pioneers()
    {
        $overseerId = 1;
        $pioneerId = 2;
        $pioneerBId = 3;
        $overseer = $this->prophesize(Publisher::class);
        $pioneer = $this->prophesize(Publisher::class);
        $pioneerB = $this->prophesize(Publisher::class);
        $overseer->isMale()->willReturn(true);
        $overseer->getId()->willReturn($overseerId);
        $pioneer->isMale()->willReturn(false);
        $pioneer->getId()->willReturn($pioneerId);
        $pioneerB->isMale()->willReturn(true);
        $pioneerB->getId()->willReturn($pioneerBId);
        $this->booking->setPublishers([$overseer->reveal(), $pioneer->reveal(), $pioneerB->reveal()]);
        static::assertSame($overseerId, $this->booking->getOverseerId());
        static::assertSame($pioneerId, $this->booking->getPioneerId());
        static::assertSame($pioneerBId, $this->booking->getPioneerBId());
        static::assertTrue($this->booking->isConfirmed());
    }
}