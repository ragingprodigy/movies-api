<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 10/12/2019, 2:38 pm.
 * @license Apache-2.0
 */

namespace App\Http\Controllers;

use App\Repositories\Series;
use Illuminate\Http\JsonResponse;

/**
 * Class TvController.
 */
class TvController extends Controller
{
    /**
     * @var Series
     */
    protected $repository;

    /**
     * TvController constructor.
     * @param Series $repository
     */
    public function __construct(Series $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'top_rated' => $this->repository->topRated(),
            'popular' => $this->repository->popular(),
            'on_air' => $this->repository->onAir(),
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function single(int $id): JsonResponse
    {
        return response()->json($this->repository->single($id));
    }
}
