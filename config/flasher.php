<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

return array(
    'default' => 'flasher',
    'root_script' => 'https://cdn.jsdelivr.net/npm/@flasher/flasher@1.0.16/dist/flasher.min.js',
    'adapters' => array(
        'toastr' => array(
            'options' => array(
                'positionClass' => 'toast-bottom-right',
            ),
        ),
    ),
);
