<?php

declare(strict_types=1);

namespace Gianiaz\ATM\Factory;

use Gianiaz\ATM\Entity\BundleMap;

class BundleMapFactory
{
    /**
     * @throws \Gianiaz\ATM\Exception\InvalidArgumentException
     */
    public static function create(array $tagli): BundleMap
    {
        return new BundleMap($tagli);
    }
}
