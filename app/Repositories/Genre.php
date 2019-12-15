<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 15/12/2019, 6:24 pm.
 * @license Apache-2.0
 */

namespace App\Repositories;

use Closure;
use Tmdb\Repository\GenreRepository;

/**
 * Class Genre.
 */
class Genre
{
    /**
     * @var GenreRepository
     */
    protected $repository;

    /**
     * Genre constructor.
     * @param GenreRepository $repository
     */
    public function __construct(GenreRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function getGenres(): array
    {
        return [
            'movies' => $this->repository->loadMovieCollection()->map($this::formatGenre())->toArray(),
            'series' => $this->repository->loadTvCollection()->map($this::formatGenre())->toArray(),
        ];
    }

    /**
     * @return Closure
     */
    public static function formatGenre(): Closure
    {
        return static function ($index, \Tmdb\Model\Genre $genre): array {
            return [
                'id' => $genre->getId(),
                'name' => $genre->getName(),
            ];
        };
    }
}
