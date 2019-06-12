<?php
declare(strict_types=1);

use Gianiaz\ATM\Entity\Atm;
use Gianiaz\ATM\Entity\BundleMap;
use PHPUnit\Framework\TestCase;

class AtmTest extends TestCase
{

    public function testFeed() {

        $denomination = 10;
        $qty = 5;

        $bundleMap = $this->prophesize(BundleMap::class);
        $bundleMap->feed($denomination, $qty)->shouldBeCalled()->willReturn('grazie');

        $atm = new Atm($bundleMap->reveal());

        $atm->feed($denomination, $qty);

    }
}
