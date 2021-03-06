<?php

namespace App\Http\Controllers;

use App\Repositories\Movies;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class MoviesController.
 */
class MoviesController extends Controller
{
    /**
     * @var Movies
     */
    protected $movies;

    /**
     * MoviesController constructor.
     * @param Movies $movies
     */
    public function __construct(Movies $movies)
    {
        $this->movies = $movies;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'top_rated' => $this->movies->topRated(),
            'popular' => $this->movies->popular(),
            'upcoming' => array_merge($this->movies->upcoming(), $this->movies->upcoming(['page' => 2])),
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function single(int $id): JsonResponse
    {
        return response()->json($this->movies->single($id));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function topRated(Request $request): JsonResponse
    {
        return response()->json($this->movies->topRated($request->all()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upcoming(Request $request): JsonResponse
    {
        return response()->json($this->movies->upcoming($request->all()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function popular(Request $request): JsonResponse
    {
        return response()->json($this->movies->popular($request->all()));
    }
}
