<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 09/12/2019, 7:10 pm.
 * @license Apache-2.0
 */

namespace App\Http\Controllers;

use App\Repositories\Movies;
use App\Repositories\Search;
use App\Repositories\Series;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class Home.
 */
class Home extends Controller
{
    /**
     * @var Movies
     */
    protected $movies;
    /**
     * @var Search
     */
    protected $search;
    /**
     * @var Series
     */
    protected $series;

    /**
     * MoviesController constructor.
     * @param Movies $movies
     * @param Search $search
     * @param Series $series
     */
    public function __construct(Movies $movies, Search $search, Series $series)
    {
        $this->movies = $movies;
        $this->search = $search;
        $this->series = $series;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'movies' => [
                'top_rated' => $this->movies->topRated(),
                'popular' => $this->movies->popular(),
                'upcoming' => $this->movies->upcoming(),
            ],
            'tv' => [
                'top_rated' => $this->series->topRated(),
                'popular' => $this->series->popular(),
                'on_air' => $this->series->onAir(),
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        return response()->json([
            'movies' => $this->search->movies($query),
            'tv' => $this->search->tv($query),
            'people' => $this->search->people($query),
        ]);
    }
}
