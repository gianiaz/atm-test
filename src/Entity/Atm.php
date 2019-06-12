<?php

declare(strict_types=1);

namespace Gianiaz\ATM\Entity;

use Gianiaz\ATM\Exception\ATMEmtpyException;
use Gianiaz\ATM\Exception\AtmWithdrawExeception;
use Gianiaz\ATM\Exception\DenominationException;
use Gianiaz\ATM\Exception\InvalidArgumentException;

class Atm
{
    /** @var BundleMap */
    private $atmBundle;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(BundleMap $bundleMap)
    {
        $this->atmBundle = $bundleMap;
    }

    /**
     * @throws InvalidArgumentException
     * @throws DenominationException
     */
    public function feed(int $denomination, int $qty): string
    {
        return $this->atmBundle->feed($denomination, $qty);
    }

    /**
     * @throws ATMEmtpyException
     * @throws DenominationException
     * @throws InvalidArgumentException
     */
    public function withdraw(int $withdrawSumm): string
    {
        if ($this->atmBundle->getTotal() < $withdrawSumm) {
            throw new ATMEmtpyException(
                'Questo ATM non è in grado di erogare la somma richiesta. E\' richiesto l\'intervento di un operatore'
            );
        }

        $withdrawBundle = new BundleMap([10, 20, 50, 100]);

        $toWithdraw = $withdrawSumm;

        $atmBundle = array_reverse($this->atmBundle->toArray());

        /** @var DenominationQty $denominationQty */
        foreach ($atmBundle as $denominationQty) {
            if (! $denominationQty->hasFunds()) {
                continue;
            }

            if ($denominationQty->getDenomination() > $toWithdraw) {
                continue;
            }

            while ($toWithdraw >= $denominationQty->getDenomination() && $denominationQty->hasFunds()) {
                $this->atmBundle->transferOneUnitTo($denominationQty, $withdrawBundle);
                $toWithdraw -= $denominationQty->getDenomination();
            }
        }

        $log = sprintf('Richiesti %s €', $withdrawSumm);
        $log .= "\n";
        $log .= $withdrawBundle;

        if ($withdrawBundle->getTotal() !== $withdrawSumm) {
            throw new AtmWithdrawExeception('La somma richiesta non è erogabile ' . $withdrawBundle->getTotal());
        }

        return $log;
    }

    public function getAtmStatus(): string
    {
        $log = 'ATM';
        $log .= $this->atmBundle;

        return $log;
    }
}
