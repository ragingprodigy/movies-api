<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 2:32 pm.
 * @license Apache-2.0
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tmdb\Client;
use Tmdb\Repository\MovieRepository;

/**
 * Class SandboxCommand.
 */
class SandboxCommand extends Command
{
    protected $name = 'sandbox';

    /**
     * @param Client $client
     */
    public function handle(Client $client): void
    {
        $repository = new MovieRepository($client);
        $movies = $repository->getUpcoming();

        foreach ($movies as $movie) {
            $this->comment(print_r($movie, true));
        }
    }
}
