<?php

use Gianiaz\ATM\Entity\Atm;
use Gianiaz\ATM\Exception\InvalidArgumentException as InvalidArgumentException;
use Gianiaz\ATM\Factory\BundleMapFactory;

require './vendor/autoload.php';


$atm = new Atm(BundleMapFactory::create([10, 20, 50, 100]));

$richiesti = (int)$argv[1];

if($richiesti <= 0) {
    throw new RuntimeException('Somma ['.$argv[1].'] non valida');
}

try {

    echo $atm->getAtmStatus();
    echo $atm->feed(10, 20);
    echo $atm->feed(20, 5);
    echo $atm->feed(50, 7);
    echo $atm->feed(100, 3);
    echo $atm->getAtmStatus()."\n";
    echo $atm->withdraw($richiesti);
    echo $atm->getAtmStatus()."\n";



} catch (InvalidArgumentException $e) {

    dump($e->getMessage());
}
