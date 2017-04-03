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
}
