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
        // $template = $this->get_template($version);
        // $model    = new Menu();
        //
        // /**
        //  * Process request
        //  */
        // view()->share([
        //      'root' => $template,
        //      'site' => $this->get_site($request),
        //  ]);
        //
		// $scripts = str_replace( 'http:', 'https:', url("/assets/v$version/that.js") );
		// $styles = str_replace( 'http:', 'https:', url("/assets/v$version/that.css") );
        //
		// // if version 1.3.0 or up is requested, make it array.
		// if ( ! empty( $version ) && version_compare( $version, '1.3.0', ">=" ) ) {
		// 	$scripts = array( $scripts );
		// 	$styles = array( $styles );
		// }
        //
        // $data = [
        //     'scripts' => $scripts,
        //     'styles'  => $styles,
        //     'that'    => $that = view($template, [
        //         'primaryMenu' => $model->getPrimaryMenu(),
        //         'desktopMenu' => $model->getDesktopMenu(),
        //         'tabletMenu'  => $model->getTabletMenu(),
        //     ])->render(),
        // ];
        //
        // // Allow protocol relative assets when running locally
        // if ( 'local' === getenv('APP_ENV') ) {
        //     $data['scripts'] = str_replace( 'https://that', '//that', $data['scripts'] );
        //     $data['styles'] = str_replace( 'https://that', '//that', $data['styles'] );
        // }
        //
        // if ($request->get('breakpoints') || $request->get('containers')) {
        //     $overrides = view("$template/styles", [
        //         'breakpoints' => $this->get_styles_data($request, 'breakpoints'),
        //         'containers'  => $this->get_styles_data($request, 'containers'),
        //     ])->render();
        //
        //     if (strlen($overrides) > 0) {
        //         $data['styles_overrides'] = $overrides;
        //     }
        // }
        //
        // /**
        //  * JSON response & JSONP callback
        //  *
        //  * @see \Illuminate\Http\JsonResponse
        //  */
        // ksort($data);
        // $response = response()->json($data)->setCallback($request->input('callback'));
        //
        // return $this->cache_json_response($response);
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
     * Parse comma-separated data for breakpoints and containers.
     * Values are colon-separated.
     *
     * Sample data format:
     *     breakpoints=large:1280,medium:1020,small:760
     *
     * @param Request $request
     * @param string $key
     *
     * @return array
     */
    protected function get_styles_data(Request $request, $key)
    {
        $data   = explode(',', filter_var($request->get($key), FILTER_SANITIZE_STRING));
        $result = [];

        foreach ((array) $data as $value) {
            $pairs = explode(':', $value);

            if (! empty($pairs[0]) && ! empty($pairs[1])) {
                $name  = $pairs[0];
                $value = $pairs[1];

                if (in_array($name, ['large', 'medium', 'small'])) {
                    $result[$name] = (int) $value;
                }
            }
        }

        return $result;
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
