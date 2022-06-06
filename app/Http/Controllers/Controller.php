<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="1.0",
     *         title="Social network API",
     *         description="API for social network site.",
     *     )
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
