<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 3:46 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Illuminate\Support\Collection;
use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Movie;

/**
 * Class AbstractRepository.
 */
abstract class AbstractRepository
{
    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * AbstractRepository constructor.
     * @param ImageHelper $imageHelper
     */
    public function __construct(ImageHelper $imageHelper)
    {
        $this->imageHelper = $imageHelper;
    }

    /**
     * @param Movie|null $movie
     * @return array|null
     */
    protected function movieDetail(Movie $movie = null): ?array
    {
        return $movie === null ? null : [
            'id' => $movie->getId(),
            'adult' => $movie->getAdult(),
            'backdrop_image_url' => $this->imageHelper->getUrl($movie->getBackdropImage(), 'w1280'),
            'budget' => $movie->getBudget(),
            'homepage' => $movie->getHomepage(),
            'original_title' => $movie->getOriginalTitle(),
            'original_language' => $movie->getOriginalLanguage(),
            'overview' => $movie->getOverview(),
            'popularity' => $movie->getPopularity(),
            'poster_image_url' => $this->imageHelper->getUrl($movie->getPosterImage(), 'w500'),
            'release_date' => $movie->getReleaseDate()->getTimestamp(),
            'revenue' => $movie->getRevenue(),
            'runtime' => $movie->getRuntime(),
            'status' => $movie->getStatus(),
            'tagline' => $movie->getTagline(),
            'title' => $movie->getTitle(),
            'vote_average' => $movie->getVoteAverage(),
            'vote_count' => $movie->getVoteCount(),
        ];
    }

    /**
     * @param Movie[] $movies
     * @return Collection
     */
    protected function formatMovies($movies): Collection
    {
        $collection = collect($movies);

        return $collection->map(function (Movie $movie) {
            return [
                'details_url' => url('/' . $movie->getId()),
                'adult' => $movie->getAdult(),
                'id' => $movie->getId(),
                'original_title' => $movie->getOriginalTitle(),
                'popularity' => $movie->getPopularity(),
                'poster_image_url' => $this->imageHelper->getUrl($movie->getPosterImage(), 'w342'),
                'release_date' => $movie->getReleaseDate()->getTimestamp(),
                'title' => $movie->getTitle(),
                'vote_average' => $movie->getVoteAverage(),
                'vote_count' => $movie->getVoteCount(),
            ];
        });
    }
}
