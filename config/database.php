<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 12:30 pm.
 * @license Apache-2.0
 */
return [
    'default' => 'default',
    'connections' => [
        'default' => [
            'driver' => 'sqlite',
            'database' => database_path('database.sqlite'),
        ],
    ],
    'migrations' => 'migration',
];
