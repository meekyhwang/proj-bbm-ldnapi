<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class TemplateController extends Controller
{

    public function getTemplates(Request $request)
    {
	    $template = new Template();
	    $templates = $template->getTemplates('template');

	    /**
	     * JSON response & JSONP callback
	     *
	     * @see \Illuminate\Http\JsonResponse
	     */
	    $response = response()->json($templates)->setCallback($request->input('callback'));

	    return $response;
    }

    public function getTemplateById(Request $request, $template_id = '')
    {
	    $template = new Template();
	    $templates = $template->getTemplateById($template_id);

	    /**
	     * JSON response & JSONP callback
	     *
	     * @see \Illuminate\Http\JsonResponse
	     */
	    $response = response()->json($templates)->setCallback($request->input('callback'));

	    return $response;
    }
}
