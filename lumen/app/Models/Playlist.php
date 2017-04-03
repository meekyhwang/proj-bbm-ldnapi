<?php

namespace App\Models;
use App\Models\Post;

class Playlist extends Post
{
    /**
     * Fetch playlists
     *
     * @return mixed
     */
    public function getPlaylists()
    {
        return $this->getPostsByPostType('playlist');
    }
    /**
     * Fetch playlist by Id
     *
     * @return mixed
     */
    public function getPlaylistById($playlist_id='')
    {
        return $this->getPostsByPostId($playlist_id, 'playlist');
    }
}
