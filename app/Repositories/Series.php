<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 10/12/2019, 2:27 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Tmdb\Helper\ImageHelper;
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
            'backdrop_image_path' => $this->imageHelper->getUrl($series->getBackdropImage(), 'w1280'),
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
            'poster_image_url' => $this->imageHelper->getUrl($series->getPosterImage(), 'w342'),
            'status' => $series->getStatus(),
            'type' => $series->getType(),
            'vote_average' => $series->getVoteAverage(),
            'vote_count' => $series->getVoteCount(),
        ];
    }
}
