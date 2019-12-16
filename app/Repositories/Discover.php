<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 17/12/2019, 12:00 am.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Model\Movie;
use Tmdb\Model\Query\Discover\DiscoverMoviesQuery;
use Tmdb\Model\Query\Discover\DiscoverTvQuery;
use Tmdb\Model\Tv;
use Tmdb\Repository\DiscoverRepository;

/**
 * Class Discover.
 */
class Discover extends AbstractRepository
{
    /**
     * @var DiscoverRepository
     */
    protected $repository;

    /**
     * Discover constructor.
     * @param ImageHelper $imageHelper
     * @param DiscoverRepository $repository
     */
    public function __construct(ImageHelper $imageHelper, DiscoverRepository $repository)
    {
        parent::__construct($imageHelper);
        $this->repository = $repository;
    }

    /**
     * @param array $options
     * @return array
     */
    public function movies(array $options = []): array
    {
        $query = new DiscoverMoviesQuery();
        $query->includeAdult(true)->includeVideo(true)
            ->page($options['page'] ?? 1)
            ->withCast([]);

        if (isset($options['year'])) {
            $query->year($options['year']);
        }

        if (isset($options['cast'])) {
            $query->withCast(explode(',', $options['cast']));
        }

        if (isset($options['genre'])) {
            $query->withGenres(explode(',', $options['genre']));
        }

        /** @var ResultCollection|Movie[] $result */
        $result = $this->repository->discoverMovies($query);
        return [
            'data' => $this->formatMovies($result)->values()->all(),
            'meta' => $this->resultMeta($result),
        ];
    }

    /**
     * @param array $options
     * @return array
     */
    public function tv(array $options = []): array
    {
        $query = new DiscoverTvQuery();
        $query->page($options['page'] ?? 1);

        if (isset($options['year'])) {
            $query->firstAirDateYear($options['year']);
        }

        if (isset($options['genre'])) {
            $query->withGenresOr(explode(',', $options['genre']));
        }

        /** @var ResultCollection|Tv[] $result */
        $result = $this->repository->discoverTv($query);
        return [
            'data' => $this->formatSeries($result)->values()->all(),
            'meta' => $this->resultMeta($result),
        ];
    }
}
