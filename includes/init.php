<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

use App\FlasherManager;
use Flasher\Noty\Prime\NotyPlugin;
use Flasher\Notyf\Prime\NotyfPlugin;
use Flasher\Pnotify\Prime\PnotifyPlugin;
use Flasher\Prime\Plugin\PluginInterface;
use Flasher\SweetAlert\Prime\SweetAlertPlugin;
use Flasher\Toastr\Prime\ToastrPlugin;

include_once __DIR__.'/../vendor/autoload.php';

include_once __DIR__.'/flasher.php';

/** @var PluginInterface[] $plugins */
$plugins = array(
    new ToastrPlugin(),
    new NotyPlugin(),
    new NotyfPlugin(),
    new PnotifyPlugin(),
    new SweetAlertPlugin(),
);

foreach ($plugins as $plugin) {
    FlasherManager::addPlugin($plugin);
}

session_start();
