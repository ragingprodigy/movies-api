<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 3:46 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Optional;
use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Movie;
use Tmdb\Model\Person;
use Tmdb\Model\Tv;

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
            return $this->multiMovieFormatter($movie);
        });
    }

    /**
     * @param Movie $movie
     * @return array
     */
    protected function multiMovieFormatter(Movie $movie): array
    {
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
    }

    /**
     * @param Tv $series
     * @return array
     */
    protected function multiTvFormatter(Tv $series): array
    {
        return [
            'details_url' => url('/tv/' . $series->getId()),
            'first_air_date' => Optional($series->getFirstAirDate())->getTimestamp(),
            'id' => $series->getId(),
            'name' => $series->getName(),
            'original_name' => $series->getOriginalName(),
            'popularity' => $series->getPopularity(),
            'poster_image_url' => $this->imageHelper->getUrl($series->getPosterImage(), 'w342'),
            'vote_average' => $series->getVoteAverage(),
            'vote_count' => $series->getVoteCount(),
        ];
    }

    /**
     * @param Person $person
     * @return array
     */
    protected function multiPersonFormatter(Person $person): array
    {
        return [
            'id' => $person->getId(),
            'name' => $person->getName(),
            'profile_path' => $this->imageHelper->getUrl($person->getProfileImage(), 'w185'),
            'gender' => $this->gender($person),
            'popularity' => $person->getPopularity(),
            'details_url' => url('/person/' . $person->getId()),
        ];
    }

    /**
     * @param Person $person
     * @return string
     */
    private function gender(Person $person): string
    {
        if ($person->isMale()) {
            return 'M';
        }

        if ($person->isFemale()) {
            return 'F';
        }

        if ($person->isUnknownGender()) {
            return 'U';
        }

        return '-';
    }
}
