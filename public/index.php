<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

include_once __DIR__.'/../includes/init.php';

flasher()->addSuccess('Data saved successfully');
toastr()->addSuccess('Data saved successfully');

header('Location: account.php');
