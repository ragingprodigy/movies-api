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
use Tmdb\Model\Collection\ResultCollection;
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
     * @param Tv[] $series
     * @return Collection
     */
    protected function formatSeries($series): Collection
    {
        $collection = collect($series);

        return $collection->map(function (Tv $series) {
            return $this->multiTvFormatter($series);
        });
    }

    /**
     * @param $people
     * @return Collection
     */
    protected function formatPeople($people): Collection
    {
        $collection = collect($people);

        return $collection->map(function (Person $people) {
            return $this->multiPersonFormatter($people);
        });
    }

    /**
     * @param Movie|Person\Credit $movie
     * @return array
     */
    protected function multiMovieFormatter($movie): array
    {
        $formatted = [
            'details_url' => url('/v1/movie/' . $movie->getId()),
            'adult' => $movie->getAdult(),
            'id' => $movie->getId(),
            'original_title' => $movie->getOriginalTitle(),
            'poster_image_url' => empty($movie->getPosterPath()) ? null : $this->imageHelper->getUrl($movie->getPosterImage(), 'w342'),
            'release_date' => Optional($movie->getReleaseDate())->getTimestamp(),
            'title' => $movie->getTitle(),
        ];

        if ($movie instanceof  Movie) {
            $formatted = array_merge($formatted, [
                'popularity' => $movie->getPopularity(),
                'vote_average' => $movie->getVoteAverage(),
                'vote_count' => $movie->getVoteCount(),
                'genre' => $movie->getGenres()->map(static function ($i, \Tmdb\Model\Genre $genre) {
                    return $genre->getId();
                })->toArray(),
            ]);
        }

        return $formatted;
    }

    /**
     * @param Tv|Person\Credit $series
     * @return array
     */
    protected function multiTvFormatter($series): array
    {
        $formatted = [
            'details_url' => url('/v1/tv/' . $series->getId()),
            'first_air_date' => Optional($series->getFirstAirDate())->getTimestamp(),
            'id' => $series->getId(),
            'name' => $series->getName(),
            'original_name' => $series->getOriginalName(),
            'poster_image_url' => empty($series->getPosterPath()) ? null : $this->imageHelper->getUrl($series->getPosterImage(), 'w342'),
        ];

        if ($series instanceof Tv) {
            $formatted = array_merge($formatted, [
                'vote_average' => $series->getVoteAverage(),
                'vote_count' => $series->getVoteCount(),
                'popularity' => $series->getPopularity(),
                'genre' => $series->getGenres()->map(static function ($i, \Tmdb\Model\Genre $genre) {
                    return $genre->getId();
                })->toArray(),
            ]);
        }

        return $formatted;
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
            'details_url' => url('/v1/person/' . $person->getId()),
        ];
    }

    /**
     * @param Person $person
     * @return string
     */
    protected function gender(Person $person): string
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

    /**
     * @param $result
     * @return array
     */
    protected function resultMeta(ResultCollection $result): array
    {
        return [
            'page' => $result->getPage(),
            'pages' => $result->getTotalPages(),
            'total' => $result->getTotalResults(),
        ];
    }
}
