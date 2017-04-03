<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PlaylistController extends Controller
{

    /**
     * Generate latest version of THAT
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getPlaylists(Request $request)
    {
        $playlist = new Playlist();
        $playlists = $playlist->getPlaylists('playlist');

        /**
         * JSON response & JSONP callback
         *
         * @see \Illuminate\Http\JsonResponse
         */
        $response = response()->json($playlists)->setCallback($request->input('callback'));

        return $response;
        // return $this->cache_json_response($response);
    }

    /**
     * Generate THAT
     *
     * @param Request $request
     * @param string $version
     *
     * @return mixed
     * @throws \Exception
     */
    public function getPlaylistById(Request $request, $playlist_id = '')
    {
        $playlist = new Playlist();
	    $playlists = $playlist->getPlaylistById($playlist_id);

	    /**
	     * JSON response & JSONP callback
	     *
	     * @see \Illuminate\Http\JsonResponse
	     */
	    $response = response()->json($playlists)->setCallback($request->input('callback'));

	    return $response;

    }

    /**
     * Locate templates for requested version
     *
     * @param string $version
     *
     * @return string
     */
    protected function get_template($version)
    {
        /**
         * Use comma for views instead of a "dot"
         * In Laravel, "dot" notation is used to reference nested views
         * @link http://laravel.com/docs/5.1/views
         */
        $template = sprintf('v%s/that', str_replace('.', ',', $version));

        if (! in_array($version, static::VERSIONS) || ! view()->exists($template)) {
            abort(404);
        }

        return $template;
    }

    /**
     * Validate site (utm_medium) param
     *
     * @param Request $request
     *
     * @return string
     */
    protected function get_site(Request $request)
    {
        $site = filter_var($request->get('site'), FILTER_SANITIZE_STRING);
        if (strlen($site) < 3) {
            abort(400, 'Missing required parameter: site');
        }

        return $site;
    }

    /**
     * Generate a list of supported versions
     *
     * @return JsonResponse
     */
    public function versions()
    {
        $response = response()->json([
            'versions' => array_map(function ($version) {
                return 'v' . $version;
            }, static::VERSIONS),
            'latest'   => 'v' . static::LATEST,
        ]);

        return $this->cache_json_response($response);
    }


}
