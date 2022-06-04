<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

namespace App;

use Flasher\Prime\Storage\Bag\BagInterface;

final class SessionBag implements BagInterface
{
    const ENVELOPES_NAMESPACE = 'flasher::envelopes';

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        if (!isset($_SESSION[self::ENVELOPES_NAMESPACE])) {
            return array();
        }

        return $_SESSION[self::ENVELOPES_NAMESPACE];
    }

    /**
     * {@inheritdoc}
     */
    public function set(array $envelopes)
    {
        $_SESSION[self::ENVELOPES_NAMESPACE] = $envelopes;
    }
}
