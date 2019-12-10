<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 10/12/2019, 3:59 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Person;
use Tmdb\Model\Person\Credit;
use Tmdb\Repository\PeopleRepository;

/**
 * Class People.
 */
class People extends AbstractRepository
{
    /**
     * @var PeopleRepository
     */
    protected $repository;

    /**
     * People constructor.
     * @param ImageHelper $imageHelper
     * @param PeopleRepository $repository
     */
    public function __construct(ImageHelper $imageHelper, PeopleRepository $repository)
    {
        parent::__construct($imageHelper);
        $this->repository = $repository;
    }

    /**
     * @param int $personId
     * @return array|null
     */
    public function single(int $personId): ?array
    {
        /** @var Person $person */
        $person = $this->repository->load($personId);

        if (null === $person) {
            return null;
        }

        return [
            'adult' => $person->getAdult(),
            'also_known_as' => $person->getAlsoKnownAs(),
            'biography' => $person->getBiography(),
            'birthday' => Optional($person->getBirthday())->getTimestamp(),
            'deathday' => Optional($person->getDeathday())->getTimestamp(),
            'id' => $person->getId(),
            'name' => $person->getName(),
            'place_of_birth' => $person->getPlaceOfBirth(),
            'profile_path' => $this->imageHelper->getUrl($person->getProfileImage(), 'w185'),
            'gender' => $this->gender($person),
            'popularity' => $person->getPopularity(),
            'movies' => array_values($person->getMovieCredits()->getCast()->map(function (string $index, Credit $credit) {
                $creditFormat = $this->multiMovieFormatter($credit);
                $creditFormat['character'] = $credit->getCharacter();

                return $creditFormat;
            })->toArray()),
            'tv' => array_values($person->getTvCredits()->getCast()->map(function (string $index, Credit $credit) {
                $creditFormat = $this->multiTvFormatter($credit);
                $creditFormat['character'] = $credit->getCharacter();

                return $creditFormat;
            })->toArray()),
        ];
    }

    /**
     * @param array $options
     * @return array
     */
    public function popular(array $options = []): array
    {
        return $this->formatPeople($this->repository->getPopular($options))->values()->all();
    }
}
