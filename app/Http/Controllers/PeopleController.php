<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 10/12/2019, 3:59 pm.
 * @license Apache-2.0
 */

namespace App\Http\Controllers;

use App\Repositories\People;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PeopleController.
 */
class PeopleController extends Controller
{
    /**
     * @var People
     */
    protected $repository;

    /**
     * PeopleController constructor.
     * @param People $repository
     */
    public function __construct(People $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'popular' => $this->repository->popular(['page' => (int) $request->get('page', 1)]),
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
