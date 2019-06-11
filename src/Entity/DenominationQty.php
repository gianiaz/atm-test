<?php

declare(strict_types=1);

namespace Gianiaz\ATM\Entity;

class DenominationQty
{
    /** @var int */
    private $denomination;

    /** @var int */
    private $qty;

    /** @var string */
    private $currency;

    public function __construct(int $denomination, int $qty = 0, string $currency = '€')
    {
        $this->denomination = $denomination;
        $this->qty = $qty;
        $this->currency = $currency;
    }

    public function getDenomination(): int
    {
        return $this->denomination;
    }

    public function getQty(): int
    {
        return $this->qty;
    }

    public function setQty(int $qty)
    {
        $this->qty = $qty;
    }

    public function withdrawOne(): int
    {
        if ($this->denomination > 1) {
            --$this->qty;

            return 1;
        }

        return 0;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTotale(): int
    {
        return $this->getDenomination() * $this->getQty();
    }

    public function hasFunds(): bool
    {
        return 0 !== $this->getQty();
    }

    public function __toString()
    {
        return sprintf(
            '%d x %d %s',
            $this->getQty(),
            $this->getDenomination(),
            $this->getCurrency()
        );
    }
}
