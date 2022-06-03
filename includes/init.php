<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

include_once __DIR__.'/../vendor/autoload.php';

include_once __DIR__.'/flasher.php';

if (PHP_SESSION_NONE === session_status()) {
    session_start();
}
