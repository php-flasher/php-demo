<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

use App\FlasherManager;
use Flasher\Prime\FlasherInterface;
use Flasher\Toastr\Prime\ToastrFactory;

/**
 * @return FlasherInterface
 */
function flasher()
{
    return FlasherManager::getFlasher();
}

/**
 * @return ToastrFactory
 */
function toastr()
{
    /** @var ToastrFactory $factory */
    $factory = flasher()->create('toastr');

    return $factory;
}

function flasher_render(array $criteria = array(), $format = 'html', array $context = array())
{
    echo flasher()->render($criteria, $format, $context);
}
