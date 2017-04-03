<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/api', function () use ($app) {
    return $app->version();
});

$app->get('/api/playlists', 'PlaylistController@getPlaylists');
$app->get('/api/playlists/{playlist_id:[0-9]+}', 'PlaylistController@getPlaylistById');


$app->get('/api/templates', 'TemplateController@getTemplates');
$app->get('/api/templates/{template_id:[0-9]+}', 'TemplateController@getTemplateById');
