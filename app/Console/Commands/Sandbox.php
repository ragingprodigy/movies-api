<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 15/12/2019, 5:54 pm.
 * @license Apache-2.0
 */

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class Sandbox.
 */
class Sandbox extends Command
{
    protected $name = 'sandbox';

    public function handle(): void
    {
        $this->info(Carbon::parse(303494400));
    }
}
