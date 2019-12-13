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
use Illuminate\Http\Request;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function topRated(Request $request): JsonResponse
    {
        return response()->json($this->repository->topRated(['page' => (int) $request->get('page', 1)]));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function onAir(Request $request): JsonResponse
    {
        return response()->json($this->repository->onAir(['page' => (int) $request->get('page', 1)]));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function popular(Request $request): JsonResponse
    {
        return response()->json($this->repository->popular(['page' => (int) $request->get('page', 1)]));
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
