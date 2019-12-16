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
use Illuminate\Validation\ValidationException;

/**
 * Class Home.
 */
class HomeController extends Controller
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
     * @throws ValidationException
     */
    public function search(Request $request): JsonResponse
    {
        $params = $this->validateRequest($request, ['q', 'page', 'year']);

        return response()->json([
            'movies' => $this->search->movies($params),
            'tv' => $this->search->tv($params),
            'people' => $this->search->people($params),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function searchPeople(Request $request): JsonResponse
    {
        $params = $this->validateRequest($request, ['q', 'page']);
        return response()->json($this->search->people($params));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function searchMovies(Request $request): JsonResponse
    {
        $params = $this->validateRequest($request, ['q', 'page', 'year']);
        return response()->json($this->search->movies($params));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function searchTv(Request $request): JsonResponse
    {
        $params = $this->validateRequest($request, ['q', 'page', 'year']);
        return response()->json($this->search->tv($params));
    }

    /**
     * @param Request $request
     * @param array $keys
     * @return array
     * @throws ValidationException
     */
    private function validateRequest(Request $request, array $keys): array
    {
        $rules = [
            'q' => 'string|required',
            'page' => 'sometimes|numeric|min:1|max:500',
            'year' => 'sometimes|date_format:Y',
        ];

        return $this->validate($request, array_filter($rules, static function ($key) use ($keys) {
            return in_array($key, $keys, true);
        }, ARRAY_FILTER_USE_KEY));
    }
}
