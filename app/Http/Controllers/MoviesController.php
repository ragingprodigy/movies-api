<?php

namespace App\Http\Controllers;

use App\Repositories\Movies;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Movie;
use Tmdb\Model\Review;
use Tmdb\Repository\ConfigurationRepository;
use Tmdb\Repository\MovieRepository;

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
            'upcoming' => $this->movies->upcoming(),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
//        $results = $this->moviesToJson($this->movieRepository->)
//        return response()->json()
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function single(int $id): JsonResponse
    {
        return response()->json($this->movies->single($id));
    }
}
