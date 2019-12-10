<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 3:46 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Common\Video;
use Tmdb\Model\Movie;
use Tmdb\Model\Person\CastMember;
use Tmdb\Model\Review;
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
            'cast' => array_values($movie->getCredits()->getCast()->map(static function (string $index, CastMember $castMember) {
                return [
                    'id' => $castMember->getId(),
                    'name' => $castMember->getName(),
                    'character' => $castMember->getCharacter(),
                    'details_url' => url('/person/' . $castMember->getId()),
                ];
            })->toArray()),
            'similar' => array_values($movie->getSimilar()->map(function (string $index, $movie) {
                return $this->multiMovieFormatter($movie);
            })->toArray()),
            'recommended' => array_values($movie->getRecommendations()->map(function (string $index, $movie) {
                return $this->multiMovieFormatter($movie);
            })->toArray()),
            'reviews' => array_values($movie->getReviews()->map(static function (string $index, Review $review) {
                return [
                    'author' => $review->getAuthor(),
                    'content' => $review->getContent(),
                ];
            })->toArray()),
            'videos' => array_values($movie->getVideos()->map(static function (string $index, Video $video) {
                return [
                    'name' => $video->getName(),
                    'type' => $video->getType(),
                    'url' => $video->getUrl(),
                ];
            })->toArray()),
        ];
    }
}
