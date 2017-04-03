<?php

namespace App\Models;
use App\Models\Post;

class Template extends Post
{
    /**
     * Fetch playlists
     *
     * @return mixed
     */
    public function getTemplates()
    {
        return $this->getPostsByPostType('template');
    }
}
