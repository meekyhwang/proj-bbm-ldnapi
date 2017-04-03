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
        return print_r($request);
    }

    public function getTemplatebyId(Request $request, $template_id = '')
    {
        return print_r($template_id);
    }
}
