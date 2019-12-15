<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 15/12/2019, 6:25 pm.
 * @license Apache-2.0
 */

namespace App\Http\Controllers;

use App\Repositories\Genre;
use Illuminate\Http\JsonResponse;

/**
 * Class GenreController.
 */
class GenreController extends Controller
{
    /**
     * @var Genre
     */
    protected $repository;

    /**
     * GenreController constructor.
     * @param Genre $repository
     */
    public function __construct(Genre $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->repository->getGenres());
    }
}
