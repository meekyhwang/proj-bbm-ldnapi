<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //

    /**
     * Add cache headers to JSON response
     *
     * @param JsonResponse $response
     * @see \Symfony\Component\HttpFoundation\Response
     *
     * @return JsonResponse
     */
    protected function cache_json_response(JsonResponse $response)
    {
        return $response->setPublic()->setCache([
            'max_age'  => 3600,
            's_maxage' => 3600,
        ])->setExpires(new \DateTime('+1 hour'));
    }
}
