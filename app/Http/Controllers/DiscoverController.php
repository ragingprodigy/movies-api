<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 17/12/2019, 12:06 am.
 * @license Apache-2.0
 */

namespace App\Http\Controllers;

use App\Repositories\Discover;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class DiscoverController.
 */
class DiscoverController extends Controller
{
    /**
     * @var Discover
     */
    protected $repository;

    /**
     * DiscoverController constructor.
     * @param Discover $repository
     */
    public function __construct(Discover $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function movies(Request $request): JsonResponse
    {
        return response()->json($this->repository->movies($request->all()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function tv(Request $request): JsonResponse
    {
        return response()->json($this->repository->tv($request->all()));
    }
}
