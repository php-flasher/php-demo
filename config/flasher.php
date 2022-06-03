<?php

/*
 * This file is part of the PHPFlasher package.
 * (c) Younes KHOUBZA <younes.khoubza@gmail.com>
 */

return array(
    'default' => 'toastr',
    'root_scripts' => array(
        'https://cdn.jsdelivr.net/npm/@flasher/flasher@1.0.16/dist/flasher.min.js',
    ),
    'adapters' => array(
        'toastr' => array(
            'scripts' => array(
                'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js',
                'https://cdn.jsdelivr.net/npm/@flasher/flasher-toastr@0.1.3/dist/flasher-toastr.min.js',
            ),
            'styles' => array(),
            'options' => array(
                'progressBar' => true,
                'timeOut' => 5000,
            ),
        ),
    ),
);
