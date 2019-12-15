<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 6:46 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Movie;
use Tmdb\Model\Person;
use Tmdb\Model\Search\SearchQuery\KeywordSearchQuery;
use Tmdb\Model\Search\SearchQuery\MovieSearchQuery;
use Tmdb\Model\Search\SearchQuery\PersonSearchQuery;
use Tmdb\Model\Search\SearchQuery\TvSearchQuery;
use Tmdb\Model\Tv;
use Tmdb\Repository\SearchRepository;

/**
 * Class Search.
 */
class Search extends AbstractRepository
{
    /**
     * @var SearchRepository
     */
    protected $repository;

    /**
     * Search constructor.
     * @param ImageHelper $imageHelper
     * @param SearchRepository $repository
     */
    public function __construct(ImageHelper $imageHelper, SearchRepository $repository)
    {
        parent::__construct($imageHelper);

        $this->repository = $repository;
    }

    /**
     * @param string $queryString
     * @param int $page
     */
    public function multiSearch(string $queryString, int $page = 1): void
    {
        $query = new KeywordSearchQuery();
        $query->page($page);

        $results = $this->repository->searchMulti($queryString, $query);
        var_dump($results);
    }

    /**
     * @param string $queryString
     * @param array $options
     * @return array
     */
    public function movies(string $queryString, array $options = []): array
    {
        $query = new MovieSearchQuery();
        $query->page($options['page'] ?? 1);
        $query->includeAdult(true);

        if (isset($options['year'])) {
            $query->year($options['year']);
        }

        $results = $this->repository->searchMovie($queryString, $query);
        return [
            'data' => array_values($results->map(function ($key, Movie $movie) {
                return $this->multiMovieFormatter($movie) + ['overview' => $movie->getOverview() ];
            })->getAll()),
            'meta' => $this->resultMeta($results),
        ];
    }

    /**
     * @param string $queryString
     * @param int $page
     * @return array
     */
    public function tv(string $queryString, int $page = 1): array
    {
        $query = new TvSearchQuery();
        $query->page($page);

        $results = $this->repository->searchTv($queryString, $query);

        return [
            'data' => array_values($results->map(function ($key, Tv $series) {
                return $this->multiTvFormatter($series);
            })->getAll()),
            'meta' => $this->resultMeta($results),
        ];
    }

    /**
     * @param string $queryString
     * @param int $page
     * @return array
     */
    public function people(string $queryString, int $page = 1): array
    {
        $query = new PersonSearchQuery();
        $query->page($page);

        $results = $this->repository->searchPerson($queryString, $query);

        return [
            'data' => array_values($results->map(function ($key, Person $person) {
                return $this->multiPersonFormatter($person);
            })->getAll()),
            'meta' => $this->resultMeta($results),
        ];
    }
}
