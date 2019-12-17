<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 17/12/2019, 12:00 am.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Carbon\Carbon;
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
        $query->includeAdult(true)->page($options['page'] ?? 1);

        $this->setReleaseDateFilter($options, $query);

        if (isset($options['genre'])) {
            $query->withGenres(explode(',', $options['genre']));
        }

        /** @var ResultCollection|Movie[] $result */
        $result = $this->repository->discoverMovies($query);
        $movies = $this->formatMovies($result);
        $meta = $this->resultMeta($result);

        if (isset($options['cast']) && !empty($options['cast'])) {
            $query = (new DiscoverMoviesQuery())->includeAdult(true)->page($options['page'] ?? 1)
                ->withCast(explode(',', $options['cast']));

            $this->setReleaseDateFilter($options, $query);

            /** @var ResultCollection|Movie[] $castResult */
            $castResult = $this->repository->discoverMovies($query);
            $withCast = $this->formatMovies($castResult);

            $movies = $movies->merge($withCast)->unique('id');
            $castMeta = $this->resultMeta($castResult);

            $meta['pages'] = max($meta['pages'], $castMeta['pages']);
        }

        return [
            'data' => $movies->values()->all(),
            'meta' => $meta,
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

        $this->setFirstAirDateFilter($options, $query);

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

    /**
     * @param array $options
     * @param DiscoverMoviesQuery $query
     */
    private function setReleaseDateFilter(array $options, DiscoverMoviesQuery $query): void
    {
        if (isset($options['year'])) {
            $years = explode(',', $options['year']);

            if (count($years) === 2) {
                $query->releaseDateGte(Carbon::now()->setYear($years[0])->startOfYear()->toDate());
                $query->releaseDateLte(Carbon::now()->setYear($years[1])->endOfYear()->toDate());
            }
        }
    }

    /**
     * @param array $options
     * @param DiscoverTvQuery $query
     */
    private function setFirstAirDateFilter(array $options, DiscoverTvQuery $query): void
    {
        if (isset($options['year'])) {
            $years = explode(',', $options['year']);

            if (count($years) === 2) {
                $query->firstAirDateGte(Carbon::now()->setYear($years[0])->startOfYear()->toDate());
                $query->firstAirDateLte(Carbon::now()->setYear($years[1])->endOfYear()->toDate());
            }
        }
    }
}
