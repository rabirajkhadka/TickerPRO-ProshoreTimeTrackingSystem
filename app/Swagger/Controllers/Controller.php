<?php

namespace App\Swagger\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0",
 *      title="TickerPro",
 *      description="Backend endpoints docs",
 *      @OA\Contact(
 *          email="support@tickerpro.com"
 *      ),
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Proshore"
 * )
 */
class Controller extends BaseController
{
}
