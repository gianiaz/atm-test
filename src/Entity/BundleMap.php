<?php

declare(strict_types=1);

namespace Gianiaz\ATM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gianiaz\ATM\Exception\DenominationException;
use Gianiaz\ATM\Exception\InvalidArgumentException;

class BundleMap extends ArrayCollection
{
    /**
     * BundleMap constructor.
     *
     * @param $denominations
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $denominations)
    {
        parent::__construct();

        foreach ($denominations as $denomination) {
            if (! is_int($denomination)) {
                throw new InvalidArgumentException('Taglio %s non valido', $denomination);
            }
            $this->add(new DenominationQty($denomination));
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add($denominationQty)
    {
        if (! $denominationQty instanceof DenominationQty) {
            throw new InvalidArgumentException('Expecting DenominationQty, got ' . get_class($denominationQty));
        }
        $this->offsetSet($denominationQty->getDenomination(), $denominationQty);
    }

    public function feed($denomination, $qty)
    {
        if (! in_array($denomination, $this->getKeys(), true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Taglio non accettato da questo ATM, tagli accettati: %s',
                    implode(', ', $this->getKeys())
                )
            );
        }

        if (! $this->containsKey($denomination)) {
            throw new DenominationException(sprintf('Taglio da %s € non presente', $denomination));
        }

        /** @var DenominationQty $denominationQty */
        $denominationQty = $this->get($denomination);
        $denominationQty->setQty($denominationQty->getQty() + $qty);

        return '- Caricate ' . $qty . ' banconote da ' . $denomination . ' €' . "\n\n";
    }

    public function withdrawDenomination(int $denomination): int
    {
        if (! $this->containsKey($denomination)) {
            throw new DenominationException(sprintf('Taglio da %s € non presente', $denomination));
        }

        /** @var DenominationQty $denominationQty */
        $denominationQty = $this->get($denomination);

        return $denominationQty->withdrawOne();
    }

    public function __toString()
    {
        $iterator = $this->getIterator();
        $iterator->ksort();

        $buffer[] = str_repeat('-', 20);
        /** @var DenominationQty $denominationQty */
        foreach ($iterator as $denominationQty) {
            $buffer[] = (string) $denominationQty;
        }
        $buffer[] = str_repeat('-', 20);
        $buffer[] = sprintf('Totale: %d €', $this->getTotal());
        $buffer[] = str_repeat('-', 20);
        $buffer[] = '';

        return "\n" . implode("\n", $buffer) . "\n";
    }

    public function getTotal(): int
    {
        $totale = 0;
        foreach ($this->getIterator() as $denominationQty) {
            $totale += $denominationQty->getTotale();
        }

        return $totale;
    }

    public function transferOneUnitTo(DenominationQty $denominationQty, BundleMap $withdraw)
    {
        /** @var DenominationQty $denominationQtyForWithDraw */
        $denominationQtyForWithDraw = $withdraw->get($denominationQty->getDenomination());

        $denominationQtyForWithDraw->setQty($denominationQtyForWithDraw->getQty() + 1);
        $denominationQty->setQty($denominationQty->getQty() - 1);
    }
}
