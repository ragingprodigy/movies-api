<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 3:46 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Carbon\Carbon;
use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Collection\ResultCollection;
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
        /** @var ResultCollection|Movie[] $result */
        $result = $this->repository->getTopRated($options);
        return [
            'data' => $this->formatMovies($result)->values()->all(),
            'meta' => $this->resultMeta($result),
        ];
    }

    /**
     * @param array $options
     * @return array
     */
    public function upcoming(array $options = []): array
    {
        /** @var ResultCollection|Movie[] $result */
        $result = $this->repository->getUpcoming($options);

        return [
            'data' => $this->formatMovies($result)
                ->sortBy('release_date')->filter(static function ($movie) {
                    return Carbon::now()->isBefore(Carbon::parse($movie['release_date']));
                })->values()->all(),
            'meta' => $this->resultMeta($result),
        ];
    }

    /**
     * @param array $options
     * @return array
     */
    public function popular(array $options = []): array
    {
        /** @var ResultCollection|Movie[] $result */
        $result = $this->repository->getPopular($options);
        return [
            'data' => $this->formatMovies($result)->sortByDesc('vote_average')->values()->all(),
            'meta' => $this->resultMeta($result),
        ];
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
            'backdrop_image_url' => empty($movie->getBackdropPath()) ? null : $this->imageHelper->getUrl($movie->getBackdropImage(), 'w1280'),
            'budget' => $movie->getBudget(),
            'homepage' => $movie->getHomepage(),
            'original_title' => $movie->getOriginalTitle(),
            'original_language' => $movie->getOriginalLanguage(),
            'overview' => $movie->getOverview(),
            'popularity' => $movie->getPopularity(),
            'poster_image_url' => empty($movie->getPosterPath()) ? null : $this->imageHelper->getUrl($movie->getPosterImage(), 'w500'),
            'release_date' => $movie->getReleaseDate()->getTimestamp(),
            'revenue' => $movie->getRevenue(),
            'runtime' => $movie->getRuntime(),
            'status' => $movie->getStatus(),
            'tagline' => $movie->getTagline(),
            'title' => $movie->getTitle(),
            'vote_average' => $movie->getVoteAverage(),
            'vote_count' => $movie->getVoteCount(),
            'genre' => $movie->getGenres()->map(static function ($i, \Tmdb\Model\Genre $genre) {
                return $genre->getId();
            })->toArray(),
            'cast' => array_values($movie->getCredits()->getCast()->map(static function (string $index, CastMember $castMember) {
                return [
                    'id' => $castMember->getId(),
                    'name' => $castMember->getName(),
                    'character' => $castMember->getCharacter(),
                    'details_url' => url('/v1/person/' . $castMember->getId()),
                    'photo_url' => url('/v1/person/' . $castMember->getId() . '/image'),
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
                    'thumbnail' => "https://img.youtube.com/vi/{$video->getKey()}/0.jpg"
                ];
            })->toArray()),
        ];
    }
}
