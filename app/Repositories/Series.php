<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 10/12/2019, 2:27 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Model\Common\Video;
use Tmdb\Model\Person\CastMember;
use Tmdb\Model\Tv;
use Tmdb\Repository\TvRepository;

/**
 * Class Series.
 */
class Series extends AbstractRepository
{
    /**
     * @var TvRepository
     */
    protected $repository;

    /**
     * Series constructor.
     * @param TvRepository $repository
     * @param ImageHelper $imageHelper
     */
    public function __construct(TvRepository $repository, ImageHelper $imageHelper)
    {
        parent::__construct($imageHelper);

        $this->repository = $repository;
    }

    /**
     * @param int $seriesId
     * @return array|null
     */
    public function single(int $seriesId): ?array
    {
        /** @var Tv $series */
        $series = $this->repository->load($seriesId);

        return $this->tvDetail($series);
    }

    /**
     * @param array $options
     * @return array
     */
    public function topRated(array $options = []): array
    {
        /** @var ResultCollection|Tv[] $result */
        $result = $this->repository->getTopRated($options);

        return $this->pagedSeries($result);
    }

    /**
     * @param array $options
     * @return array
     */
    public function onAir(array $options = []): array
    {
        /** @var ResultCollection|Tv[] $result */
        $result = $this->repository->getOnTheAir($options);

        return $this->pagedSeries($result);
    }

    /**
     * @param array $options
     * @return array
     */
    public function popular(array $options = []): array
    {
        /** @var ResultCollection|Tv[] $result */
        $result = $this->repository->getPopular($options);

        return [
            'data' => $this->formatSeries($result)->sortByDesc('vote_count')->values()->all(),
            'meta' => $this->resultMeta($result),
        ];
    }

    /**
     * @param Tv|null $series
     * @return array|null
     */
    public function tvDetail(Tv $series = null): ?array
    {
        if (null === $series) {
            return null;
        }

        return [
            'id' => $series->getId(),
            'backdrop_image_path' => empty($series->getBackdropPath()) ? null : $this->imageHelper->getUrl($series->getBackdropImage(), 'w1280'),
            'created_by' => $series->getCreatedBy(),
            'episode_run_time' => $series->getEpisodeRunTime(),
            'first_air_date' => Optional($series->getFirstAirDate())->getTimestamp(),
            'last_air_date' => Optional($series->getLastAirDate())->getTimestamp(),
            'in_production' => $series->getInProduction(),
            'name' => $series->getName(),
            'number_of_episodes' => $series->getNumberOfEpisodes(),
            'number_of_seasons' => $series->getNumberOfSeasons(),
            'original_language' => $series->getOriginalLanguage(),
            'original_name' => $series->getOriginalName(),
            'overview' => $series->getOverview(),
            'popularity' => $series->getPopularity(),
            'poster_image_url' => empty($series->getPosterPath()) ? null : $this->imageHelper->getUrl($series->getPosterImage(), 'w342'),
            'status' => $series->getStatus(),
            'type' => $series->getType(),
            'vote_average' => $series->getVoteAverage(),
            'vote_count' => $series->getVoteCount(),
            'genre' => $series->getGenres()->map(static function ($i, \Tmdb\Model\Genre $genre) {
                return $genre->getId();
            }),
            'cast' => array_values($series->getCredits()->getCast()->map(static function (string $index, CastMember $castMember) {
                return [
                    'id' => $castMember->getId(),
                    'name' => $castMember->getName(),
                    'character' => $castMember->getCharacter(),
                    'details_url' => url('/v1/person/' . $castMember->getId()),
                    'photo_url' => url('/v1/person/' . $castMember->getId() . '/image'),
                ];
            })->toArray()),
            'similar' => array_values($series->getSimilar()->map(function (string $index, $tv) {
                return $this->multiTvFormatter($tv);
            })->toArray()),
            'recommended' => array_values($series->getRecommendations()->map(function (string $index, $tv) {
                return $this->multiTvFormatter($tv);
            })->toArray()),
            'videos' => array_values($series->getVideos()->map(static function (string $index, Video $video) {
                return [
                    'name' => $video->getName(),
                    'type' => $video->getType(),
                    'url' => $video->getUrl(),
                    'thumbnail' => "https://img.youtube.com/vi/{$video->getKey()}/0.jpg"
                ];
            })->toArray()),
        ];
    }

    /**
     * @param $result
     * @return array
     */
    private function pagedSeries($result): array
    {
        return [
            'data' => $this->formatSeries($result)->values()->all(),
            'meta' => $this->resultMeta($result),
        ];
    }
}
