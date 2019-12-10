<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 3:46 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Movie;
use Tmdb\Repository\MovieRepository;

/**
 * Class Movies.
 */
class Movies extends AbstractRepository
{
    /**
     * @var MovieRepository
     */
    protected $repository;

    /**
     * Movies constructor.
     * @param MovieRepository $repository
     * @param ImageHelper $imageHelper
     */
    public function __construct(MovieRepository $repository, ImageHelper $imageHelper)
    {
        parent::__construct($imageHelper);

        $this->repository = $repository;
    }

    /**
     * @param int $movieId
     * @return array|null
     */
    public function single(int $movieId): ?array
    {
        /** @var Movie $movie */
        $movie = $this->repository->load($movieId);

        return $this->movieDetail($movie);
    }

    /**
     * @param array $options
     * @return array
     */
    public function topRated(array $options = []): array
    {
        return $this->formatMovies($this->repository->getTopRated($options))->values()->all();
    }

    /**
     * @param array $options
     * @return array
     */
    public function upcoming(array $options = []): array
    {
        return $this->formatMovies($this->repository->getUpcoming($options))
            ->sortBy('release_date')->values()->all();
    }

    /**
     * @param array $options
     * @return array
     */
    public function popular(array $options = []): array
    {
        return $this->formatMovies($this->repository->getPopular($options))
            ->sortBy('vote_count')->values()->all();
    }
}
